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

namespace arkania\database;

interface SqlThread {
	public const MODE_GENERIC = 0;
	public const MODE_CHANGE  = 1;
	public const MODE_INSERT  = 2;
	public const MODE_SELECT  = 3;

	/**
	 * @see https://php.net/thread.join Thread::join
	 */
	public function join();

	public function stopRunning() : void;

	/**
	 * @param mixed[] $params
	 */
	public function addQuery(int $queryId, int $modes, string $queries, array $params) : void;

	/**
	 * @param callable[] $callbacks
	 */
	public function readResults(array &$callbacks, ?int $expectedResults) : void;

	public function connCreated() : bool;

	public function hasConnError() : bool;

	public function getConnError() : ?string;

}
