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

namespace arkania\packs;

use arkania\Engine;
use pocketmine\resourcepacks\ZippedResourcePack;
use Symfony\Component\Filesystem\Path;

class ResourcePackManager {
	private Engine $engine;

	/** @var string[] */
	protected array $resourcePackPath;

	public function __construct(
		Engine $engine
	) {
		$this->engine           = $engine;
		$this->resourcePackPath = [];
		$this->registerResourcePack(
			'Arkania-Pack',
			new ResourcesPackFile(
				Path::join($engine->getEngineFile(), 'packs', 'Arkania-Pack')
			)
		);
	}

	public function registerResourcePack(string $packName, ResourcesPackFile $packFile) : void {
		$this->resourcePackPath[$packName] = $packFile->getResourcePackPath();
		$packFile->savePackInData($packFile->getResourcePackPath());
		$packFile->zipPack(
			$packFile->getResourcePackPath(),
			Path::join($this->engine->getEngineFile(), 'packs'),
			$packName
		);
	}

	public function loadResourcePack() : void {
		$resourcePackManager = $this->engine->getServer()->getResourcePackManager();
		$resourcePacks       = [];
		foreach ($this->resourcePackPath as $packName => $packPath) {
			$resourcePacks[] = new ZippedResourcePack($packPath . '.zip');
		}
		$ev = new ResourcePackLoadEvent();
		$ev->call();
		if (!$ev->isCancelled()) {
			if ($ev->getResourcePackPath() !== null) {
				foreach ($ev->getResourcePackPath() as $packName => $resource) {
					$resourcePacks[] = new ZippedResourcePack($resource . '.zip');
				}
			}
			$resourcePackManager->setResourcePacksRequired(true);
			$resourcePackManager->setResourceStack($resourcePacks);
		} else {
			$this->engine->getLogger()->warning('Resources pack system is cancelled !');
		}
	}

}
