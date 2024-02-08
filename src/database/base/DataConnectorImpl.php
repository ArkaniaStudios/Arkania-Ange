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

use arkania\database\DataConnector;
use arkania\database\SqlError;
use arkania\database\SqlThread;
use arkania\utils\promise\Deferred;
use arkania\utils\promise\PromiseInterface;
use Logger;
use pocketmine\plugin\Plugin;
use function count;

class DataConnectorImpl implements DataConnector {
	private Plugin $plugin;
	private SqlThread $thread;
	private ?Logger $logger = null;
	private int $queryId    = 0;
	/** @var callable[] */
	private array $handlers = [];

	public function __construct(
		Plugin $plugin,
		SqlThread $sqlThread,
		bool $logQueries = false
	) {
		$this->plugin = $plugin;
		if($sqlThread instanceof SqlThreadPool) {
			$sqlThread->setDataConnector($this);
		}
		$this->thread = $sqlThread;
		$this->logger = $logQueries ? $plugin->getLogger() : null;
	}

	public function setLoggingQueries(bool $loggingQueries) : void {
		$this->logger = $loggingQueries ? $this->plugin->getLogger() : null;
	}

	public function getLogger() : ?Logger {
		return $this->logger;
	}

	public function setLogger(?Logger $logger) : void {
		$this->logger = $logger;
	}

	public function executeGeneric(string $query, array $params = []) : PromiseInterface {
		return $this->executeImplRaw($query, SqlThread::MODE_GENERIC, $params);
	}

	public function executeInsert(string $query, array $params = []) : PromiseInterface {
		return $this->executeImplRaw($query, SqlThread::MODE_INSERT, $params);
	}

	public function executeSelect(string $query, array $params = []) : PromiseInterface {
		return $this->executeImplRaw($query, SqlThread::MODE_SELECT, $params);
	}

	public function executeChange(string $query, array $params = []) : PromiseInterface {
		return $this->executeImplRaw($query, SqlThread::MODE_CHANGE, $params);
	}

	private function executeImplRaw(string $query, int $mode, array $params) : PromiseInterface {
		$queryId                  = $this->queryId++;
		$def                      = new Deferred();
		$this->handlers[$queryId] = function ($results) use ($def) : void {
			if($results instanceof SqlError) {
				$def->reject($results);
			} else {
				$def->resolve($results[0]);
			}
		};
		$this->thread->addQuery($queryId, $mode, $query, $params);
		return $def->promise();
	}

	public function waitAll() : void {
		while(!empty($this->handlers)) {
			$this->thread->readResults($this->handlers, count($this->handlers));
		}
	}

	public function checkResults() : void {
		$this->thread->readResults($this->handlers, null);
	}

	public function close() : void {
		$this->thread->stopRunning();
	}

	public function isLoggingQueries() : bool {
		return $this->logger !== null;
	}

}
