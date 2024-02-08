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

use arkania\utils\promise\PromiseInterface;
use Logger;

interface DataConnector {
	public function setLoggingQueries(bool $loggingQueries) : void;

	public function isLoggingQueries() : bool;

	public function setLogger(?Logger $logger) : void;

	public function getLogger() : ?Logger;

	public function executeGeneric(string $query, array $params = []) : PromiseInterface;

	public function executeInsert(string $query, array $params = []) : PromiseInterface;

	public function executeChange(string $query, array $params = []) : PromiseInterface;

	public function executeSelect(string $query, array $params = []) : PromiseInterface;

	public function waitAll() : void;

	public function close() : void;

}
