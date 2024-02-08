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

namespace arkania\rank\trait;

use arkania\database\result\SqlSelectResult;
use arkania\Engine;
use JsonException;
use pocketmine\utils\Config;
use Symfony\Component\Filesystem\Path;
use function count;
use function file_exists;
use function json_encode;
use function mkdir;
use const JSON_THROW_ON_ERROR;

trait RankPersistenceTrait {
	protected string $path;

	protected function __construct(string $path) {
		$this->path = $path;
	}

	public function save(bool $hasConfig = false) : bool {
		if($hasConfig === true) {
			if(!file_exists($this->path)) {
				mkdir($this->path, 0777, true);
			}

			if($this->isStaff() === true) {
				$this->save();
				return true;
			}

			$config = new Config(Path::join($this->path, $this->getName() . '.yml'), Config::YAML);
			try {
				$config->setAll([
					'name'        => $this->getName(),
					'description' => $this->getDescription(),
					'chat'        => $this->getChatFormatter()->getFormat(),
					'nameTag'     => $this->getNameTagFormatter()->getFormat(),
					'color'       => $this->getColor(),
					'prefix'      => $this->getPrefix(),
					'priority'    => $this->getPriority(),
					'isDefault'   => $this->isDefault(),
					'isStaff'     => $this->isStaff(),
					'permissions' => $this->getPermissions()
				]);
				$config->save();
			} catch (JsonException) {
				return false;
			}
		} else {
			Engine::getInstance()->getDataBaseManager()->getConnector()->executeSelect(
				'SELECT * FROM ranks WHERE name = ?',
				[
					$this->getName()
				]
			)->then(
				function (SqlSelectResult $result) : void {
					if(count($result->getRows()) === 0) {
						Engine::getInstance()->getDataBaseManager()->getConnector()->executeInsert(
							'INSERT INTO ranks (name, description, chat, nameTag, color, prefix, priority, isDefault, isStaff, permissions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
							[
								$this->getName(),
								$this->getDescription(),
								$this->getChatFormatter()->getFormat(),
								$this->getNameTagFormatter()->getFormat(),
								$this->getColor(),
								$this->getPrefix(),
								$this->getPriority(),
								$this->isDefault(),
								$this->isStaff(),
								json_encode($this->getPermissions(), JSON_THROW_ON_ERROR)
							]
						);
					} else {
						Engine::getInstance()->getDataBaseManager()->getConnector()->executeChange(
							'UPDATE ranks SET description = ?, chat = ?, nameTag = ?, color = ?, prefix = ?, priority = ?, isDefault = ?, isStaff = ?, permissions = ? WHERE name = ?',
							[
								$this->getDescription(),
								$this->getChatFormatter()->getFormat(),
								$this->getNameTagFormatter()->getFormat(),
								$this->getColor(),
								$this->getPrefix(),
								$this->getPriority(),
								$this->isDefault(),
								$this->isStaff(),
								json_encode($this->getPermissions(), JSON_THROW_ON_ERROR),
								$this->getName()
							]
						);
					}
				}
			);
			return true; //Useless return maybe delete it
		}
		return false; // wtf is this return
	}
}
