<?php
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

class DataPacketSendListener implements EngineListener {

    public function onDataPacketSend(DataPacketSendEvent $event) : void {
        $itemManager = Engine::getInstance()->getItemManager();
        foreach ($event->getPackets() as $packet) {
            if ($packet instanceof BiomeDefinitionListPacket) {
                $sessions = $event->getTargets();
                foreach ($sessions as $session) {
                    $session->getPlayer()->getNetworkSession()->sendDataPacket(ItemComponentPacket::create(
                        $itemManager->getComponentsEntries())
                    );
                }
            }elseif($packet instanceof StartGamePacket) {
                $packet->itemTable = array_merge($packet->itemTable, $itemManager->getItemsEntries());
            }elseif($packet instanceof ResourcePackStackPacket) {
                $packet->experiments = new Experiments([
                    "data_driven_items" => true
                ], true);
            }
        }
    }

}