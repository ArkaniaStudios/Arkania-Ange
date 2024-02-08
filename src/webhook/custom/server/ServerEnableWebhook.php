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

namespace arkania\webhook\custom\server;

use arkania\webhook\class\Webhook;
use function memory_get_usage;
use function round;

class ServerEnableWebhook extends Webhook {
	public function send(
		string $serverName,
		string $serverIp,
		int $serverPort,
		int $serverProtocol,
		string $serverVersion,
		string $serverApiVersion,
		int $serverMaxPlayers,
		int $serverOnlinePlayers,
		string $phpVersion
	) : void {
		$embed = $this->getEmbed();
		$embed->setDescription(
			'- Le serveur **' . $serverName . '** a été démarré avec succès !' . "\n\n" .
			'*Informations:*' . "\n" .
			' - IP: `' . $serverIp . ':' . $serverPort . '`' . "\n" .
			' - Version: `' . $serverVersion . '`' . "\n" .
			' - API: `' . $serverApiVersion . '`' . "\n" .
			' - Joueurs: `' . $serverOnlinePlayers . '/' . $serverMaxPlayers . '`' . "\n" .
			' - PHP: `' . $phpVersion . '`' . "\n" .
			' - Protocol: `' . $serverProtocol . '`' . "\n" .
			' - Memory: `' . round(memory_get_usage() / 1024 / 1024, 2) . 'MB`'
		);
		$this->submit();
	}

}
