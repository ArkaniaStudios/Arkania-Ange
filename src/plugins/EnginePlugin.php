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

namespace arkania\plugins;

use arkania\Engine;
use AttachableLogger;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;

interface EnginePlugin {
	public function __construct(
		Engine $engine,
		PluginLoader $loader,
		Server $server,
		PluginInformations $informations,
		string $dataFolder,
		string $file,
		ResourceProvider $resourceProvider
	);

	public function isEnabled() : bool;

	public function onEnableStateChange(bool $enabled) : void;

	public function getDataFolder() : string;

	public function getInformations() : PluginInformations;

	public function getName() : string;

	public function getLogger() : AttachableLogger;

	public function getLoader() : PluginLoader;

	public function getEngine() : Engine;

	public function getScheduler() : TaskScheduler;

}
