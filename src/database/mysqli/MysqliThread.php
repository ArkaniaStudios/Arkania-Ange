<?php

/*
 *     _      ____    _  __     _      _   _   ___      _              _____   _   _    ____   ___   _   _   _____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            | ____| | \ | |  / ___| |_ _| | \ | | | ____|
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____  |  _|   |  \| | | |  _   | |  |  \| | |  _|
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____| | |___  | |\  | | |_| |  | |  | |\  | | |___
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\         |_____| |_| \_|  \____| |___| |_| \_| |_____|
 *
 * ArkaniaStudios-ANGE, une API conçue pour simplifier le développement.
 * Fournissant des outils et des fonctionnalités aux développeurs.
 * Cet outil est en constante évolution et est régulièrement mis à jour,
 * afin de répondre aux besoins changeants de la communauté.
 *
 * @author Julien
 * @link https://arkaniastudios.com
 * @version 0.2.0-beta
 *
 */

declare(strict_types=1);

namespace arkania\database\mysqli;

use arkania\database\base\QueryRecvQueue;
use arkania\database\base\QuerySendQueue;
use arkania\database\base\SqlSlaveThread;
use arkania\database\result\SqlChangeResult;
use arkania\database\result\SqlColumnInfo;
use arkania\database\result\SqlInsertResult;
use arkania\database\result\SqlSelectResult;
use arkania\database\SqlError;
use arkania\database\SqlResult;
use arkania\database\SqlThread;
use Closure;
use InvalidArgumentException;
use mysqli;
use mysqli_result;
use mysqli_sql_exception;
use mysqli_stmt;
use pocketmine\snooze\SleeperHandlerEntry;
use pocketmine\thread\log\AttachableThreadSafeLogger;
use function array_map;
use function assert;
use function bccomp;
use function bcsub;
use function count;
use function gettype;
use function implode;
use function in_array;
use function is_float;
use function is_int;
use function is_string;
use function min;
use function mysqli_report;
use function serialize;
use function sleep;
use function strtotime;
use function strval;
use function unserialize;
use const MYSQLI_REPORT_OFF;
use const PHP_INT_MAX;

class MysqliThread extends SqlSlaveThread {
	private string $credentials;

	private AttachableThreadSafeLogger $logger;

	public static function createFactory(MysqlCredentials $credentials, AttachableThreadSafeLogger $logger) : Closure {
		return function (SleeperHandlerEntry $sleeperEntry, QuerySendQueue $bufferSend, QueryRecvQueue $bufferRecv) use ($credentials, $logger) {
			return new MysqliThread($credentials, $sleeperEntry, $logger, $bufferSend, $bufferRecv);
		};
	}

	public function __construct(
		MysqlCredentials $credentials,
		SleeperHandlerEntry $entry,
		AttachableThreadSafeLogger $logger,
		QuerySendQueue $bufferSend = null,
		QueryRecvQueue $bufferRecv = null
	) {
		$this->credentials = serialize($credentials);
		$this->logger      = $logger;
		parent::__construct($entry, $bufferSend, $bufferRecv);
	}

	protected function createConn(&$resource) : ?string {
		/** @var MysqlCredentials $cred */
		$cred = unserialize($this->credentials);
		try {
			$resource = $cred->newMysqli();
			return null;
		} catch (SqlError $e) {
			return $e->getErrorMessage();
		}
	}

	protected function executeQuery($resource, int $mode, string $query, array $params) : SqlResult {
		assert($resource instanceof mysqli);
		/** @var MysqlCredentials $cred */
		$cred = unserialize($this->credentials);
		$ping = false;
		mysqli_report(MYSQLI_REPORT_OFF);
		try {
			$ping = @$resource->ping();
		} catch (mysqli_sql_exception) {
		}

		if (!$ping) {
			$success  = false;
			$attempts = 0;
			do {
				$second = min(2 ** $attempts, PHP_INT_MAX);
				$this->logger->warning("Attempting to reconnect to MySQL server in $second seconds...");
				sleep($second);
				try {
					$cred->reconnectMysqli($resource);
					$success = true;
				} catch (SqlError) {
					$attempts++;
				}
			} while(!$success);
			$this->logger->info("Successfully reconnected to MySQL server.");
		}

		if (count($params) === 0) {
			$result = @$resource->query($query);
			if ($result === false) {
				throw new SqlError(SqlError::STAGE_EXECUTE, $resource->error, $query, []);
			}
			switch ($mode) {
				case SqlThread::MODE_GENERIC:
				case SqlThread::MODE_CHANGE:
				case SqlThread::MODE_INSERT:
					if($result instanceof mysqli_result) {
						$result->close();
					}
					if($mode === SqlThread::MODE_INSERT) {
						return new SqlInsertResult($resource->affected_rows, $resource->insert_id);
					}
					if($mode === SqlThread::MODE_CHANGE) {
						return new SqlChangeResult($resource->affected_rows);
					}
					return new SqlResult();
				case SqlThread::MODE_SELECT:
					$ret = $this->toSelectResult($result);
					$result->close();
					return $ret;
			}
		} else {
			$stmt = $resource->prepare($query);
			if(!($stmt instanceof mysqli_stmt)) {
				throw new SqlError(SqlError::STAGE_EXECUTE, $resource->error, $query, $params);
			}
			$types = implode(array_map(static function ($param) use ($query, $params) {
				if(is_string($param)) {
					return "s";
				}
				if(is_int($param)) {
					return "i";
				}
				if(is_float($param)) {
					return "d";
				}
				throw new SqlError(SqlError::STAGE_EXECUTE, "Unsupported parameter type " . gettype($param), $query, $params);
			}, $params));
			$stmt->bind_param($types, ...$params);
			if(!$stmt->execute()) {
				throw new SqlError(SqlError::STAGE_PREPARE, $stmt->error, $query, $params);
			}
			switch ($mode) {
				case SqlThread::MODE_GENERIC:
					$ret = new SqlResult();
					$stmt->close();
					return $ret;
				case SqlThread::MODE_CHANGE:
					$ret = new SqlChangeResult($stmt->affected_rows);
					$stmt->close();
					return $ret;
				case SqlThread::MODE_INSERT:
					$ret = new SqlInsertResult($stmt->affected_rows, $stmt->insert_id);
					$stmt->close();
					return $ret;
				case SqlThread::MODE_SELECT:
					$set = $stmt->get_result();
					$ret = $this->toSelectResult($set);
					$set->close();
					return $ret;
			}
		}
		throw new InvalidArgumentException("Unknown mode $mode");
	}

	private function toSelectResult(mysqli_result $result) : SqlSelectResult {
		$columns    = [];
		$columnFunc = [];

		while(($field = $result->fetch_field()) !== false) {
			if($field->length === 1) {
				if($field->type === MysqlTypes::TINY) {
					$type                     = SqlColumnInfo::TYPE_BOOL;
					$columnFunc[$field->name] = static function ($tiny) {
						return $tiny > 0;
					};
				} elseif($field->type === MysqlTypes::BIT) {
					$type                     = SqlColumnInfo::TYPE_BOOL;
					$columnFunc[$field->name] = static function ($bit) {
						return $bit === "\1";
					};
				}
			}
			if($field->type === MysqlTypes::LONGLONG) {
				$type                     = SqlColumnInfo::TYPE_INT;
				$columnFunc[$field->name] = static function ($longLong) use ($field) {
					if($field->flags & MysqliFlags::UNSIGNED_FLAG) {
						if(bccomp(strval($longLong), "9223372036854775807") === 1) {
							$longLong = bcsub($longLong, "18446744073709551616");
						}
						return (int) $longLong;
					}

					return (int) $longLong;
				};
			} elseif($field->flags & MysqliFlags::TIMESTAMP_FLAG) {
				$type                     = SqlColumnInfo::TYPE_TIMESTAMP;
				$columnFunc[$field->name] = static function ($stamp) {
					return strtotime($stamp);
				};
			} elseif($field->type === MysqlTypes::NULL) {
				$type = SqlColumnInfo::TYPE_NULL;
			} elseif(in_array($field->type, [
				MysqlTypes::VARCHAR,
				MysqlTypes::STRING,
				MysqlTypes::VAR_STRING,
			], true)) {
				$type = SqlColumnInfo::TYPE_STRING;
			} elseif(in_array($field->type, [MysqlTypes::FLOAT, MysqlTypes::DOUBLE, MysqlTypes::DECIMAL, MysqlTypes::NEWDECIMAL], true)) {
				$type                     = SqlColumnInfo::TYPE_FLOAT;
				$columnFunc[$field->name] = "floatval";
			} elseif(in_array($field->type, [MysqlTypes::TINY, MysqlTypes::SHORT, MysqlTypes::INT24, MysqlTypes::LONG], true)) {
				$type                     = SqlColumnInfo::TYPE_INT;
				$columnFunc[$field->name] = "intval";
			}
			if(!isset($type)) {
				$type = SqlColumnInfo::TYPE_OTHER;
			}
			$columns[$field->name] = new MysqlColumnInfo($field->name, $type, $field->flags, $field->type);
		}

		$rows = [];
		while(($row = $result->fetch_assoc()) !== null) {
			foreach($row as $col => &$cell) {
				if($cell !== null && isset($columnFunc[$col])) {
					$cell = $columnFunc[$col]($cell);
				}
			}
			unset($cell);
			$rows[] = $row;
		}

		return new SqlSelectResult($columns, $rows);
	}

	protected function close(&$resource) : void {
		assert($resource instanceof mysqli);
		$resource->close();
	}

	public function getThreadName() : string {
		return __NAMESPACE__ . " connector #$this->slaveNumber";
	}

}
