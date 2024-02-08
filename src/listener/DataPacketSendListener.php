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

namespace arkania\listener;

use arkania\events\EngineListener;
use arkania\utils\Loader;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AvailableActorIdentifiersPacket;
use pocketmine\utils\AssumptionFailedError;

class DataPacketSendListener implements EngineListener {
	public function onDataPacketSend(\pocketmine\event\server\DataPacketSendEvent $event) : void {
		foreach ($event->getPackets() as $packet) {
			if($packet instanceof AvailableActorIdentifiersPacket) {
				$customNamespaces = Loader::getCustomNamespaces();
				$base             = $packet->identifiers->getRoot();
				$nbt              = $base->getListTag("idlist");
				foreach ($customNamespaces as $index => $namespace) {
					$components = CompoundTag::create()
						->setString("bid", "")
						->setByte("hasspawnegg", 0)
						->setString("id", $namespace)
						->setInt("rid", 200 + $index)
						->setByte("summonable", 1);
					if($nbt === null) {
						throw new AssumptionFailedError("\$nbt === null");
					}
					$nbt->push($components);
				}
			}
		}
	}

}
