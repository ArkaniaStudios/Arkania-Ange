<?php

namespace arkania\sounds;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class SoundsList {
    use SingletonTrait;

    public function validSound(Player $player): void {
        Sound::getInstance()->playSound($player, "note.bell");
    }

    public function errorSound(Player $player): void {
        Sound::getInstance()->playSound($player, "note.bass");
    }
}