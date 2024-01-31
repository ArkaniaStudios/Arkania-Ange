<?php
declare(strict_types=1);

namespace arkania\listener;

use arkania\events\EngineListener;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\Session;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;

class PlayerReceiveFormListener implements EngineListener {

    public function onReceiveForm(DataPacketSendEvent $event) : void {
        $packet = $event->getPackets();
        foreach ($packet as $pk) {
            if ($pk instanceof ModalFormRequestPacket) {
                $data = json_decode($pk->formData, true);
                if ($data['permissions'] !== null){
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