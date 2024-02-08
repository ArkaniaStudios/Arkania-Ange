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

namespace arkania\commands\default\sub;

use arkania\commands\CommandBase;
use arkania\database\result\SqlSelectResult;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\network\server\ServerInterface;
use arkania\network\server\ServersIds;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use function count;

class MaintenanceOnSubCommand extends CommandBase {
	/**
	 * @throws MissingPermissionException
	 */
	public function __construct(
		private readonly Engine $engine
	) {
		parent::__construct(
			'on'
		);
		$this->setPermission(PermissionsBase::getPermission('maintenance'));
	}

	public function getCommandParameter() : array {
		return [
			//TODO: Implémenter le système de maintenance depuis un autre serveur.
		];
	}

	public function onRun(CommandSender $sender, array $parameters) : void {
		if($sender instanceof Player) {
			$sender = Session::get($sender);
		}
		$server = $this->engine->getServerManager()->getServer(ServersIds::getIdWithPort($this->engine->getServer()->getPort()));
		if($server === null) {
			$sender->sendMessage(KnownTranslationsFactory::command_maintenance_error());
			return;
		}

		$server->getStatus()->then(
			function (SqlSelectResult $result) use ($sender) : void {
				if (count($result->getRows()) <= 0) {
					//wtf impossible
					return;
				}
				if($result->getRows()[0]['status'] === ServerInterface::STATUS_WHITELIST) {
					$sender->sendMessage(KnownTranslationsFactory::command_maintenance_already_on());
					return;
				}

				$this->engine->getDataBaseManager()->getConnector()->executeChange(
					'UPDATE servers SET status =? WHERE id = ?',
					[
						ServerInterface::STATUS_WHITELIST,
						$result->getRows()[0]['id']
					]
				)->then(
					function () use ($sender) : void {
						foreach (Server::getInstance()->getOnlinePlayers() as $player) {
							$player->disconnect(Session::get($player)->getLanguage()->translate(KnownTranslationsFactory::command_maintenance_disconnect()));
						}
						$sender->sendMessage(KnownTranslationsFactory::command_maintenance_on());
					}
				);
			}
		);
	}
}
