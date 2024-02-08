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

use pmmp\thread\ThreadSafe;
use pmmp\thread\ThreadSafeArray;
use function serialize;

class QuerySendQueue extends ThreadSafe {
	private bool $invalidated = false;

	private ThreadSafeArray $queries;

	public function __construct() {
		$this->queries = new ThreadSafeArray();
	}

	public function scheduleQuery(int $queryID, int $modes, string $queries, array $params) : void {
		if($this->invalidated) {
			throw new QueueShutdownException("You cannot schedule a query on an invalidated queue.");
		}
		$this->synchronized(function () use ($queryID, $modes, $queries, $params) : void {
			$this->queries[] = serialize([$queryID, $modes, $queries, $params]);
			$this->notifyOne();
		});
	}

	public function fetchQuery() : ?string {
		return $this->synchronized(function () : ?string {
			while ($this->queries->count() === 0 && !$this->invalidated) {
				$this->wait();
			}
			return $this->queries->shift();
		});
	}

	public function invalidate() : void {
		$this->synchronized(function () : void {
			$this->invalidated = true;
			$this->notify();
		});
	}

	public function count() : int {
		return $this->queries->count();
	}

}
