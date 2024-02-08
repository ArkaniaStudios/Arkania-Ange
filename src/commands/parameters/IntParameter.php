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
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use function preg_match;

class IntParameter extends Parameter {
	private bool $acceptNegative;

	public function __construct(
		string $name,
		bool $acceptNegative = false,
		bool $isOptional = false
	) {
		parent::__construct($name, $isOptional);
		$this->acceptNegative = $acceptNegative;
	}

	public function getNetworkType() : int {
		return AvailableCommandsPacket::ARG_TYPE_INT;
	}

	public function canParse(string $argument, CommandSender $sender) : bool {
		return (bool) preg_match(
			"/^" . ($this->acceptNegative ? "-?" : "") . "[0-9]+$/",
			$argument
		);
	}

	public function parse(string $argument, CommandSender $sender) : int {
		return (int) $argument;
	}

}
