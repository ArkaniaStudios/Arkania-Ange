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

namespace arkania\player;

use arkania\database\result\SqlSelectResult;
use arkania\Engine;
use pocketmine\player\Player;
use WeakMap;
use function count;
use function serialize;
use function time;

trait SessionCacheTrait {
	/**
	 * @phpstan-var WeakMap<Player, Session>
	 */
	private static WeakMap $data;

	public static function get(Player $player) : self {
		if(!isset(self::$data)) {
			$data       = new WeakMap();
			self::$data = $data;
		}
		return self::$data[$player] ?? self::loadSession($player);
	}

	private static function loadSession(Player $player) : self {
		return new Session($player->getNetworkSession());
	}

	public static function create(Player $player) : void {
		Engine::getInstance()->getDataBaseManager()->getConnector()->executeGeneric(
			'CREATE TABLE IF NOT EXISTS players(
                uuid VARCHAR(36) NOT NULL,
                language VARCHAR(20) NOT NULL,
                permissions TEXT NOT NULL,
                last_ip VARCHAR(255) NOT NULL,
                last_login BIGINT NOT NULL,
                last_logout BIGINT NOT NULL,
                first_login BIGINT NOT NULL,
                play_time BIGINT NOT NULL,
                play_time_today BIGINT NOT NULL,
                `rank` VARCHAR(32) DEFAULT NULL,
                rank_expiration BIGINT DEFAULT NULL
            )'
		)->then(function () use ($player) {
			Engine::getInstance()->getDataBaseManager()->getConnector()->executeSelect(
				'SELECT * FROM players WHERE uuid = ?',
				[
					$player->getUniqueId()->__toString()
				]
			)->then(function (SqlSelectResult $result) use ($player) {
				if (count($result->getRows()) <= 0) {
					Engine::getInstance()->getDataBaseManager()->getConnector()->executeInsert(
						'INSERT INTO players(
                uuid,
                language,
                permissions,
                last_ip,
                last_login,
                last_logout,
                first_login,
                play_time,
                play_time_today,
                `rank`,
                rank_expiration
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?)',
						[
							$player->getUniqueId()->toString(),
							'french',
							serialize([]),
							$player->getNetworkSession()->getIp(),
							time(),
							0,
							time(),
							0,
							0,
							'Joueur',
							-1
						]
					);
				}
			});
		});

		Engine::getInstance()->getDataBaseManager()->getConnector()->executeSelect(
			'SELECT * FROM players WHERE uuid = ?',
			[
				$player->getUniqueId()->__toString()
			]
		)->then(function (SqlSelectResult $result) use ($player) : void {
			$session = self::get($player);
			if(count($result->getRows()) <= 0) {
				return;
			}
			$session->setLanguage(Engine::getInstance()->getLanguageManager()->getLanguage($result->getRows()[0]['language']));
			self::syncAvailableCommands($player);
		});
	}

}
