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

namespace arkania\utils\promise\internal;

use Throwable;

use function array_push;
use function is_object;
use function key;
use function method_exists;

class CancellationQueue {
	private bool $started = false;
	private array $queue  = [];

	/**
	 * @throws Throwable
	 */
	public function __invoke() : void {
		if ($this->started) {
			return;
		}

		$this->started = true;
		$this->drain();
	}

	/**
	 * @throws Throwable
	 */
	public function enqueue($cancellable) : void {
		if (!is_object($cancellable) || !method_exists($cancellable, 'then') || !method_exists($cancellable, 'cancel')) {
			return;
		}

		$length = array_push($this->queue, $cancellable);

		if ($this->started && 1 === $length) {
			$this->drain();
		}
	}

	/**
	 * @throws Throwable
	 */
	private function drain() : void {
		for ($i = key($this->queue); isset($this->queue[$i]); $i++) {
			$cancellable = $this->queue[$i];

			$exception = null;

			try {
				$cancellable->cancel();
			} catch (Throwable $exception) {
			}

			unset($this->queue[$i]);

			if ($exception) {
				throw $exception;
			}
		}

		$this->queue = [];
	}

}
