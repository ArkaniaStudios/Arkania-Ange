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

namespace arkania\commands;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;
use pocketmine\Server;

class EnumStore {
	/** @var CommandEnum[] */
	private static array $enum = [];

	public static function getEnum(string $name) : ?CommandEnum {
		return self::$enum[$name] ?? null;
	}

	/**
	 * @return CommandEnum[]
	 */
	public static function getEnums() : array {
		return self::$enum;
	}

	public static function addEnum(CommandEnum $enum) : void {
		self::$enum[$enum->getName()] = $enum;
		self::broadcastEnum($enum, UpdateSoftEnumPacket::TYPE_ADD);
	}

	public static function broadcastEnum(CommandEnum $enum, int $type) : void {
		$pk           = new UpdateSoftEnumPacket();
		$pk->enumName = $enum->getName();
		$pk->values   = $enum->getValues();
		$pk->type     = $type;
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			$player->getNetworkSession()->sendDataPacket($pk);
		}
	}

}
