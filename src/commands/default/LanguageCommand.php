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
use arkania\commands\parameters\LanguageParameter;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\lang\LanguageManager;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LanguageCommand extends CommandBase {
	/**
	 * @throws MissingPermissionException
	 */
	public function __construct() {
		parent::__construct(
			'language',
			KnownTranslationsFactory::command_language_description(),
			'/language <language>',
			[],
			[
				'l',
				'lang'
			]
		);
		$this->setPermission(PermissionsBase::getPermission('base'));
	}

	public function getCommandParameter() : array {
		return [
			new LanguageParameter(
				'language',
				'language'
			)
		];
	}

	public function onRun(CommandSender $sender, array $parameters) : void {
		if(!$sender instanceof Player) {
			return;
		}

		$language = $parameters['language'];

		$sender = Session::get($sender);
		$sender->setLanguage(
			Engine::getInstance()->getLanguageManager()->getLanguage(LanguageManager::parseLanguageName($language))
		);
		$sender->sendMessage(
			KnownTranslationsFactory::command_language_changed(
				$language
			)
		);
	}

}
