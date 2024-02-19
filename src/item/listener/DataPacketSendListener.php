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

namespace arkania\item\listener;

use arkania\Engine;
use arkania\events\EngineListener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\BiomeDefinitionListPacket;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\Experiments;
use function array_merge;

class DataPacketSendListener implements EngineListener {
	public function onDataPacketSend(DataPacketSendEvent $event) : void {
		$itemManager = Engine::getInstance()->getItemManager();
		foreach ($event->getPackets() as $packet) {
			if ($packet instanceof BiomeDefinitionListPacket) {
				$sessions = $event->getTargets();
				foreach ($sessions as $session) {
					$session->getPlayer()->getNetworkSession()->sendDataPacket(
						ItemComponentPacket::create(
							$itemManager->getComponentsEntries()
						)
					);
				}
			} elseif($packet instanceof StartGamePacket) {
				$packet->itemTable    = array_merge($packet->itemTable, $itemManager->getItemsEntries());
			} elseif($packet instanceof ResourcePackStackPacket) {
				$packet->experiments = new Experiments([
					"data_driven_items" => true
				], true);
			}
		}
	}

}
