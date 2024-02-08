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

namespace arkania\commands\default;

use arkania\commands\CommandBase;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use const PHP_VERSION;

class VersionCommand extends CommandBase {
	/**
	 * @throws MissingPermissionException
	 */
	public function __construct() {
		parent::__construct(
			'version',
			KnownTranslationsFactory::command_version_description(),
			'/version',
			[],
			[
				'v'
			]
		);
		$this->setPermission(PermissionsBase::getPermission('base'));
	}

	public function getCommandParameter() : array {
		return [];
	}

	public function onRun(CommandSender $sender, array $parameters) : void {
		if($sender instanceof Player) {
			$sender = Session::get($sender);
		}
		$sender->sendMessage(
			KnownTranslationsFactory::command_version_message(
				Engine::getInstance()->getServerName(),
				Engine::getInstance()->getApiVersion(),
				PHP_VERSION,
				Utils::getOS(),
				ProtocolInfo::MINECRAFT_VERSION_NETWORK
			)
		);
	}

}
