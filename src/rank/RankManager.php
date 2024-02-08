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

namespace arkania\rank;

use arkania\database\result\SqlSelectResult;
use arkania\Engine;
use arkania\rank\class\ChatFormatter;
use arkania\rank\class\NameTagFormatter;
use arkania\rank\class\Rank;
use function json_decode;
use const JSON_THROW_ON_ERROR;

class RankManager {
	/** @var Rank[] */
	private array $ranks = [];

	public function __construct(
		Engine $engine
	) {
		$engine->getDataBaseManager()->getConnector()->executeGeneric(
			'CREATE TABLE IF NOT EXISTS ranks (
                name VARCHAR(16) PRIMARY KEY,
                description VARCHAR(255),
                chat VARCHAR(255),
                nameTag VARCHAR(255),
                color VARCHAR(255),
                prefix VARCHAR(255),
                priority INT,
                isDefault BOOLEAN,
                isStaff BOOLEAN,
                permissions TEXT
            )'
		)->then(function () : void {
			$this->loadRank();
		});
	}

	private function loadRank() : void {
		Engine::getInstance()->getDataBaseManager()->getConnector()->executeSelect(
			'SELECT * FROM ranks'
		)->then(
			function (SqlSelectResult $result) : void {
				foreach ($result->getRows() as $row) {
					$this->ranks[$row['name']] = new Rank(
						$row['name'],
						$row['description'],
						new ChatFormatter($row['chat']),
						new NameTagFormatter($row['nameTag']),
						$row['color'],
						$row['prefix'],
						$row['priority'],
						$row['isDefault'],
						$row['isStaff'],
						json_decode($row['permissions'], true, 512, JSON_THROW_ON_ERROR)
					);
				}
			}
		);
	}

	public function getRank(string $name) : ?Rank {
		return $this->ranks[$name] ?? null;
	}

	public function getRanks() : array {
		return $this->ranks;
	}

}
