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

namespace arkania\lang;

use pocketmine\lang\LanguageNotFoundException;
use pocketmine\lang\Translatable;
use pocketmine\utils\Utils;
use Symfony\Component\Filesystem\Path;

use function array_map;
use function count;
use function file_exists;
use function parse_ini_file;
use function str_replace;
use const INI_SCANNER_RAW;
use const pocketmine\LOCALE_DATA_PATH;

class Language extends \pocketmine\lang\Language {
	public function __construct(
		string $langName,
		string $lang,
		?string $path = null,
		?string $fallbackPath = null,
		string $fallback = \pocketmine\lang\Language::FALLBACK_LANGUAGE
	) {
		$this->langName = $langName;

		if($path === null) {
			$path = LOCALE_DATA_PATH;
		}

		$this->lang         = self::loadLang($path, $lang);
		$this->fallbackLang = self::loadLang($fallbackPath, $fallback);
	}

	public function getName() : string {
		return $this->langName;
	}

	public function getLang() : string {
		return $this->get(KnownTranslationsKeys::LANGUAGE_NAME);
	}

	protected static function loadLang(string $path, string $languageCode) : array {
		$file = Path::join($path, $languageCode);
		if(file_exists($file)) {
			$strings = array_map('stripcslashes', Utils::assumeNotFalse(parse_ini_file($file, false, INI_SCANNER_RAW), "Missing or inaccessible required resource files"));
			if(count($strings) > 0) {
				return $strings;
			}
		}
		throw new LanguageNotFoundException("Language \"$languageCode\" not found");
	}

	public function translate(Translatable $c) : string {
		$baseText = $this->internalGet($c->getText());
		if($baseText === null) {
			$baseText = $this->parseTranslation($c->getText());
		}

		foreach($c->getParameters() as $i => $p) {
			$replacement = $p instanceof Translatable ? $this->translate($p) : $p;
			$baseText    = str_replace("{%$i}", $replacement, $baseText);
			$baseText    = str_replace('\n', "\n", $baseText);
		}

		return $baseText;
	}

}
