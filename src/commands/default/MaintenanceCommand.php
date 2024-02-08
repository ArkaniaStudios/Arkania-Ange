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
use arkania\commands\default\sub\MaintenanceOffSubCommand;
use arkania\commands\default\sub\MaintenanceOnSubCommand;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use pocketmine\command\CommandSender;

class MaintenanceCommand extends CommandBase {
	/**
	 * @throws MissingPermissionException
	 */
	public function __construct(
		Engine $engine
	) {
		parent::__construct(
			'maintenance',
			KnownTranslationsFactory::command_maintenance_description(),
			'/maintenance <on:off>',
			[
				new MaintenanceOnSubCommand($engine),
				new MaintenanceOffSubCommand($engine)
			]
		);
		$this->setPermission(PermissionsBase::getPermission('maintenance'));
	}

	public function getCommandParameter() : array {
		return [];
	}

	public function onRun(CommandSender $sender, array $parameters) : void {
	}

}
