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

use arkania\Engine;
use arkania\events\lang\RegisterLanguageEvent;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Path;
use function dirname;
use function file_exists;
use function mkdir;

class LanguageManager implements NotOtherInstanceInterface {
	use NotOtherInstanceTrait {
		NotOtherInstanceTrait::__construct as private __notOtherInstanceTraitConstruct;
	}

	/** @var array<string, Language> */
	private array $languages = [];
	private Engine $engine;

	public function __construct(
		Engine $engine
	) {
		$this->__notOtherInstanceTraitConstruct();

		if(!file_exists(Path::join($engine->getDataFolder(), 'data'))) {
			mkdir(Path::join($engine->getDataFolder(), 'data'));
		}
		$engine->saveResource(Path::join('data', 'fr_FR.lang'), true);
		$engine->saveResource(Path::join('data', 'en_US.lang'), true);

		$this->register(
			new Language(
				'Français',
				'fr_FR.lang',
				Path::join($engine->getDataFolder(), 'data'),
				Path::join(dirname(__DIR__, 2), 'vendor', 'pocketmine', 'locale-data'),
				'fra.ini'
			)
		);
		$this->register(
			new Language(
				'English',
				'en_US.lang',
				Path::join($engine->getDataFolder(), 'data'),
				Path::join(dirname(__DIR__, 2), 'vendor', 'pocketmine', 'locale-data'),
				'eng.ini'
			)
		);
		$this->engine = $engine;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function register(Language $language) : void {
		if(isset($this->languages[$language->getName()])) {
			throw new InvalidArgumentException("Language with name {$language->getName()} already registered");
		}
		$ev = new RegisterLanguageEvent($language);
		$ev->call();
		if($ev->isCancelled()) {
			return;
		}
		$this->languages[$language->getName()] = $language;
	}

	public function getLanguage(string $lang) : ?Language {
		return $this->languages[$lang] ?? null;
	}

	public function getConsoleLanguage() : Language {
		return $this->languages[self::parseLanguageName($this->engine->getConfig()->get('console-language'))];
	}

	public function getDefaultLanguage() : Language {
		return $this->languages[self::parseLanguageName($this->engine->getConfig()->get('default-language'))];
	}

	public static function parseLanguageName(string $lang) : string {
		return match($lang) {
			'english' => 'English',
			default   => 'Français'
		};
	}

}
