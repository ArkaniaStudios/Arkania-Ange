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

namespace arkania\network\server;

use arkania\Engine;
use arkania\utils\promise\PromiseInterface;

interface ServerInterface {
	const STATUS_ONLINE     = 'online';
	const STATUS_OFFLINE    = 'offline';
	const STATUS_STARTING   = 'starting';
	const STATUS_STOPPING   = 'stopping';
	const STATUS_RESTARTING = 'restarting';
	const STATUS_CRASHED    = 'crashed';
	const STATUS_WHITELIST  = 'whitelist';

	public function getId() : int;

	public function getName() : string;

	public function getIp() : string;

	public function getPort() : int;

	public function getStatus() : PromiseInterface;

	public function getEngine() : Engine;

	public function getStringStatus() : string;

	public function setStatus(string $status) : void;

}
