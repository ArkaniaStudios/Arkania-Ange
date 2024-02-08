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
use arkania\lang\KnownTranslationsFactory;
use pocketmine\plugin\PluginDescriptionParseException;
use pocketmine\thread\ThreadSafeClassLoader;
use Symfony\Component\Filesystem\Path;
use function file_exists;
use function file_get_contents;
use function is_dir;

class FolderPluginLoader implements PluginLoader {
	public function __construct(
		private readonly ThreadSafeClassLoader $loader
	) {
	}

	public function canLoad(string $path) : bool {
		return is_dir($path) && file_exists(Path::join($path, "/plugin.yml"));
	}
	public function loadPlugin(string $file) : void {
		$description = $this->getPluginInfo($file);
		if($description !== null) {
			$this->loader->addPath($description->getSrcNamespacePrefix(), "$file/src");
		}
	}
	public function getPluginInfo(string $file) : ?PluginInformations {
		if(is_dir($file) && file_exists($file . "/plugin.yml")) {
			$yaml = @file_get_contents($file . "/plugin.yml");
			if($yaml !== '') {
				try {
					return new PluginInformations($yaml);
				} catch (PluginDescriptionParseException) {
					Engine::getInstance()->getLogger()->error(
						Engine::getInstance()->getLanguage()->translate(
							KnownTranslationsFactory::plugin_invalid_plugin_file(
								$file . "/plugin.yml"
							)
						)
					);
					return null;
				}
			}
		}
		return null;
	}

	public function getAccessProtocol() : string {
		return "";
	}

}
