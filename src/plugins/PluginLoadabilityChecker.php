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

use pocketmine\lang\KnownTranslationFactory;
use pocketmine\lang\Translatable;
use pocketmine\plugin\ApiVersion;
use pocketmine\utils\VersionString;
use function count;
use function implode;
use function stripos;

class PluginLoadabilityChecker {
	public function __construct(
		private readonly string $apiVersion
	) {
	}

	public function check(PluginInformations $informations) : null|Translatable {
		$name = $informations->getName();
		if(stripos($name, "pocketmine") !== false || stripos($name, "minecraft") !== false || stripos($name, "mojang") !== false || stripos($name, 'arkania') !== false) {
			return KnownTranslationFactory::pocketmine_plugin_restrictedName();
		}

		foreach($informations->getApi() as $api) {
			if(!VersionString::isValidBaseVersion($api)) {
				return KnownTranslationFactory::pocketmine_plugin_invalidAPI($api);
			}
		}

		if(!ApiVersion::isCompatible($this->apiVersion, $informations->getApi())) {
			return KnownTranslationFactory::pocketmine_plugin_incompatibleAPI(implode(", ", $informations->getApi()));
		}

		$ambiguousVersions = ApiVersion::checkAmbiguousVersions($informations->getApi());
		if(count($ambiguousVersions) > 0) {
			return KnownTranslationFactory::pocketmine_plugin_ambiguousMinAPI(implode(", ", $ambiguousVersions));
		}
		return null;
	}

}
