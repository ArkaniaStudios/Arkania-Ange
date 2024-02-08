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
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use arkania\plugins\EnginePlugin;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function array_map;
use function count;
use function implode;
use function sort;
use const SORT_STRING;

class PluginCommand extends CommandBase {
	public function __construct() {
		parent::__construct(
			'plugins',
			KnownTranslationsFactory::command_plugin_description(),
			'/plugins',
			[],
			[
				'pl',
				'plugin'
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

		$list = array_map(function (EnginePlugin $plugin) : string {
			return ($plugin->isEnabled() ? TextFormat::GREEN : TextFormat::RED) . $plugin->getInformations()->getFullName();
		}, Engine::getInstance()->getServerLoader()->getPluginManager()->getPlugins());
		sort($list, SORT_STRING);

		$sender->sendMessage(
			KnownTranslationsFactory::command_plugin_list(
				(string) count($list),
				implode(TextFormat::RESET . ", ", $list)
			)
		);
	}

}
