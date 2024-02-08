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

namespace arkania\commands\parameters;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use function array_keys;
use function array_map;
use function implode;
use function preg_match;
use function strtolower;

abstract class EnumParameter extends Parameter {
	protected array $values = [];

	public function __construct(
		string $name,
		string $enumName,
		bool $isOptional = false
	) {
		parent::__construct($name, $isOptional);
		$this->getCommandParameter()->enum = new CommandEnum($enumName, $this->getEnumValues());
	}

	public function getNetworkType() : int {
		return -1;
	}

	public function canParse(string $argument, CommandSender $sender) : bool {
		return (bool) preg_match(
			"/^(" . implode("|", array_map('\\strtolower', $this->getEnumValues())) . ")$/iu",
			$argument
		);
	}

	public function addValue(string $string, bool|float|int|string $value) : void {
		$this->values[strtolower($string)] = $value;
	}

	/**
	 * @return string[]|bool[]|int[]|float[]
	 */
	public function getEnumValues() : array {
		return array_keys($this->values);
	}

}
