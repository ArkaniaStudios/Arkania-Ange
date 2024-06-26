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

use arkania\commands\CommandCache;
use arkania\Engine;
use AttachableLogger;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use function rtrim;
use const DIRECTORY_SEPARATOR;

abstract class EnginePluginBase implements EnginePlugin {
	private TaskScheduler $scheduled;
	private bool $isEnabled = false;

	public function __construct(
		private readonly Engine $engine,
		private readonly PluginLoader $loader,
		private readonly Server $server,
		private readonly PluginInformations $informations,
		private string $dataFolder,
		private string $file,
		private readonly ResourceProvider $resourceProvider
	) {
		$this->dataFolder = rtrim($this->dataFolder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->file       = rtrim($this->file, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->scheduled  = new TaskScheduler($this->getFullName());
		$this->onLoad();
	}

	protected function onLoad() : void {
	}

	protected function onEnable() : void {
	}

	protected function onDisable() : void {
	}

	final public function getEngine() : Engine {
		return $this->engine;
	}

	final public function getLoader() : PluginLoader {
		return $this->loader;
	}

	final public function getServer() : Server {
		return $this->server;
	}

	final public function getInformations() : PluginInformations {
		return $this->informations;
	}

	final public function getDataFolder() : string {
		return $this->dataFolder;
	}

	final public function getFile() : string {
		return $this->file;
	}

	final public function getLogger() : AttachableLogger {
		return $this->engine->getLogger();
	}

	final public function getResourceProvider() : ResourceProvider {
		return $this->resourceProvider;
	}

	final public function getScheduler() : TaskScheduler {
		return $this->scheduled;
	}

	final public function isEnabled() : bool {
		return $this->isEnabled;
	}

	final public function getFullName() : string {
		return $this->informations->__toString();
	}

	final public function getName() : string {
		return $this->informations->getName();
	}

	final public function getCommandCache() : CommandCache {
		return $this->engine->getCommandCache();
	}

	final public function onEnableStateChange(bool $enabled) : void {
		if($this->isEnabled !== $enabled) {
			$this->isEnabled = $enabled;
			if($this->isEnabled) {
				$this->onEnable();
			} else {
				$this->onDisable();
			}
		}
	}
}
