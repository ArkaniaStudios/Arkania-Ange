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
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

abstract class Parameter {
	private string $name;
	private bool $isOptional;
	private CommandParameter $commandParameter;

	public function __construct(
		string $name,
		bool $isOptional = false
	) {
		$this->name                         = $name;
		$this->isOptional                   = $isOptional;
		$this->commandParameter             = new CommandParameter();
		$this->commandParameter->paramName  = $name;
		$this->commandParameter->isOptional = $isOptional;
		$this->commandParameter->paramType  = AvailableCommandsPacket::ARG_FLAG_VALID;
		$this->commandParameter->paramType |= $this->getNetworkType();
	}

	abstract public function getNetworkType() : int;
	abstract public function parse(string $argument, CommandSender $sender) : mixed;
	abstract public function canParse(string $argument, CommandSender $sender) : bool;

	public function getName() : string {
		return $this->name;
	}

	public function isOptional() : bool {
		return $this->isOptional;
	}

	public function getCommandParameter() : CommandParameter {
		return $this->commandParameter;
	}

	public function getSpanLength() : int {
		return 1;
	}

}
