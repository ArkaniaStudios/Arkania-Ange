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

namespace arkania;

use Phar;
use pocketmine\utils\Git;
use pocketmine\utils\VersionString;
use function is_array;
use function is_int;
use function str_repeat;

final class VersionInfo {
	public const NAME                 = "ArkaniaStudios-Ange";
	public const BASE_VERSION         = "1.0.0";
	public const IS_DEVELOPMENT_BUILD = true;
	public const BUILD_CHANNEL        = "stable";

	private function __construct() {
	}

	private static ?string $gitHash = null;

	public static function GIT_HASH() : string {
		if(self::$gitHash === null) {
			$gitHash = str_repeat("00", 20);

			if(Phar::running() === "") {
				$gitHash = Git::getRepositoryStatePretty(PATH);
			} else {
				$phar = new Phar(Phar::running(false));
				$meta = $phar->getMetadata();
				if(isset($meta["git"])) {
					$gitHash = $meta["git"];
				}
			}

			self::$gitHash = $gitHash;
		}

		return self::$gitHash;
	}

	private static ?int $buildNumber = null;

	public static function BUILD_NUMBER() : int {
		if(self::$buildNumber === null) {
			self::$buildNumber = 0;
			if(Phar::running() !== "") {
				$phar = new Phar(Phar::running(false));
				$meta = $phar->getMetadata();
				if(is_array($meta) && isset($meta["build"]) && is_int($meta["build"])) {
					self::$buildNumber = $meta["build"];
				}
			}
		}

		return self::$buildNumber;
	}

	private static ?VersionString $fullVersion = null;

	public static function VERSION() : VersionString {
		if(self::$fullVersion === null) {
			self::$fullVersion = new VersionString(self::BASE_VERSION, self::IS_DEVELOPMENT_BUILD, self::BUILD_NUMBER());
		}
		return self::$fullVersion;
	}
}
