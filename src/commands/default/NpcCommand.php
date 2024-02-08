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
use arkania\commands\parameters\SubParameter;
use arkania\npc\FormManager;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function strtolower;

class NpcCommand extends CommandBase {
	public static array $npc = [];

	/**
	 * @throws MissingPermissionException
	 */
	public function __construct() {
		parent::__construct(
			'npc',
			'Manage the NPCs',
			'/npc'
		);
		$this->setPermission(PermissionsBase::getPermission('npc'));
	}

	public function getCommandParameter() : array {
		return [
			new SubParameter('create', 'create', true),
			new SubParameter('disband', 'disband', true),
			new SubParameter('rotate', 'rotate', true),
			new SubParameter('edit', 'edit', true)
		];
	}

	public function onRun(CommandSender $sender, array $parameters) : void {
		if(!$sender instanceof Player) {
			return;
		}

		if($parameters === []) {
			FormManager::getInstance()->sendNpcCreationForm($sender);
			return;
		}
		$argument = strtolower($parameters['create']);
		if($argument === 'create') {
			FormManager::getInstance()->sendNpcCreationForm($sender);
		} elseif($argument === 'disband') {
			self::$npc[$sender->getName()] = 'disband';
			$sender->sendMessage('§cTap on the NPC to disband it');
		} elseif($argument === 'rotate') {
			self::$npc[$sender->getName()] = 'rotate';
			$sender->sendMessage('§cTap on the NPC to rotate it');
		} elseif($argument === 'edit') {
			self::$npc[$sender->getName()] = 'edit';
			$sender->sendMessage('§cTap on the NPC to edit it');
		}
	}

}
