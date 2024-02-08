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

namespace arkania\database\base;

use arkania\database\SqlError;
use arkania\database\SqlResult;
use arkania\database\SqlThread;
use InvalidArgumentException;
use pocketmine\snooze\SleeperHandlerEntry;
use pocketmine\thread\Thread;
use function is_string;
use function unserialize;

abstract class SqlSlaveThread extends Thread implements SqlThread {
	private SleeperHandlerEntry $sleeperEntry;

	private static int $nextSlaveNumber = 0;

	protected int $slaveNumber;
	protected QuerySendQueue $bufferSend;
	protected QueryRecvQueue $bufferRecv;
	protected bool $connCreated = false;
	protected ?string $connError;
	protected bool $busy = false;

	public function __construct(
		SleeperHandlerEntry $entry,
		QuerySendQueue $bufferSend = null,
		QueryRecvQueue $bufferRecv = null
	) {
		$this->sleeperEntry = $entry;

		$this->slaveNumber = self::$nextSlaveNumber++;
		$this->bufferSend  = $bufferSend ?? new QuerySendQueue();
		$this->bufferRecv  = $bufferRecv ?? new QueryRecvQueue();

		$this->start(\pmmp\thread\Thread::INHERIT_INI);
	}

	protected function onRun() : void {
		$error             = $this->createConn($resource);
		$this->connCreated = true;
		$this->connError   = $error;

		$notifier = $this->sleeperEntry->createNotifier();

		if($error !== null) {
			return;
		}

		while(true) {
			$row = $this->bufferSend->fetchQuery();
			if(!is_string($row)) {
				break;
			}
			$this->busy                           = true;
			[$queryId, $modes, $queries, $params] = unserialize($row, ["allowed_classes" => true]);

			try {
				$results   = [];
				$results[] = $this->executeQuery($resource, $modes, $queries, $params);
				$this->bufferRecv->publishResult($queryId, $results);
			} catch(SqlError $error) {
				$this->bufferRecv->publishError($queryId, $error);
			}

			$notifier->wakeupSleeper();
			$this->busy = false;
		}
		$this->close($resource);
	}

	public function isBusy() : bool {
		return $this->busy;
	}

	public function stopRunning() : void {
		$this->bufferSend->invalidate();
		parent::quit();
	}

	public function quit() : void {
		$this->stopRunning();
		parent::quit();
	}

	public function addQuery(int $queryId, int $modes, string $queries, array $params) : void {
		$this->bufferSend->scheduleQuery($queryId, $modes, $queries, $params);
	}

	public function readResults(array &$callbacks, ?int $expectedResults) : void {
		if($expectedResults === null) {
			$resultsList = $this->bufferRecv->fetchAllResults();
		} else {
			$resultsList = $this->bufferRecv->waitForResults($expectedResults);
		}
		foreach ($resultsList as [$queryID, $results]) {
			if(!isset($callbacks[$queryID])) {
				throw new InvalidArgumentException("Missing handler for query #$queryID");
			}
			$callbacks[$queryID]($results);
			unset($callbacks[$queryID]);
		}
	}

	public function connCreated() : bool {
		return $this->connCreated;
	}

	public function hasConnError() : bool {
		return $this->connError !== null;
	}

	public function getConnError() : ?string {
		return $this->connError;
	}

	abstract protected function createConn(&$resource) : ?string;

	abstract protected function executeQuery($resource, int $mode, string $query, array $params) : SqlResult;
	abstract protected function close(&$resource) : void;

}
