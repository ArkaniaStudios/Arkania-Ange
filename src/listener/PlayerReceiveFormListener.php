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
use arkania\lang\KnownTranslationsFactory;
use arkania\player\Session;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use function json_decode;

class PlayerReceiveFormListener implements EngineListener {
	public function onReceiveForm(DataPacketSendEvent $event) : void {
		$packet = $event->getPackets();
		foreach ($packet as $pk) {
			if ($pk instanceof ModalFormRequestPacket) {
				$data = json_decode($pk->formData, true);
				if ($data['permissions'] !== null) {
					foreach ($event->getTargets() as $networkSession) {
						$player = $networkSession->getPlayer();
						if (!$player->hasPermission($data['permissions'])) {
							Session::get($networkSession->getPlayer())->sendMessage(
								KnownTranslationsFactory::form_cant_open()
							);
							$event->cancel();
						}
					}
				}
			}
		}
	}

}
