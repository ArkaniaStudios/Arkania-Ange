<?php

namespace arkania\utils;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class Utils {
    use SingletonTrait;

    public function playSound(Player $player, string $soundName, int $volume = 100, int $pitch = 1): void {
        $player->getNetworkSession()->sendDataPacket(
            PlaySoundPacket::create(
                $soundName,
                $player->getPosition()->getX(),
                $player->getPosition()->getY(),
                $player->getPosition()->getZ(),
                $volume,
                $pitch
            )
        );
    }
}