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
use arkania\commands\parameters\TextParameter;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class ReplyCommand extends CommandBase {
	/**
	 * @throws MissingPermissionException
	 */
	public function __construct() {
		parent::__construct(
			'reply',
			KnownTranslationsFactory::command_reply_description(),
			'/reply <message>',
			[],
			[
				'r'
			]
		);
		$this->setPermission(PermissionsBase::getPermission('base'));
	}

	public function getCommandParameter() : array {
		return [
			new TextParameter(
				'message'
			)
		];
	}

	public function onRun(CommandSender $sender, array $parameters) : void {
		$message = $parameters['message'];

		if($sender instanceof Player) {
			$sender = Session::get($sender);
		}

		if($sender->getLastPlayerMessage() === null) {
			$sender->sendMessage(KnownTranslationsFactory::command_reply_no_player());
			return;
		}

		$target = Server::getInstance()->getPlayerExact($sender->getLastPlayerMessage());

		if($target === null) {
			$sender->sendMessage(KnownTranslationsFactory::player_not_found(
				$sender->getLastPlayerMessage()
			));
			return;
		}

		$target = Session::get($target);
		$target->sendMessage(KnownTranslationsFactory::command_tell_message_sent(
			$sender->getName(),
			$message
		));
		$sender->sendMessage(KnownTranslationsFactory::command_tell_message_received(
			$target->getName(),
			$message
		));
	}

}
