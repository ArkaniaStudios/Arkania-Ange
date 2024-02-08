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

use InvalidArgumentException;

final class ServersIds {
	private static array $serversPort = [];

	private static array $serversIds = [];

	private static array $serversName = [];

	public static function addServer(int $port, int $serverId, string $name) : void {
		self::$serversPort[$port]    = $serverId;
		self::$serversIds[$serverId] = $port;
		self::$serversName[$name]    = $serverId;
	}

	public static function getIdWithPort(int $port) : int {
		return self::$serversPort[$port] ?? throw new InvalidArgumentException("Invalid port $port you can use `addServer`");
	}

	public static function getServerWithName(string $name) : int {
		return self::$serversName[$name] ?? throw new InvalidArgumentException("Invalid server name $name you can use `addServer`");
	}

	public static function getPortWithId(int $id) : int {
		return self::$serversIds[$id] ?? throw new InvalidArgumentException("Invalid server name $id you can use `addServer`");
	}

}
