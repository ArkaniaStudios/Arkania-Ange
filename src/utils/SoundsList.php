<?php

namespace arkania\utils;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class SoundsList {
    use SingletonTrait;

    public function validSound(Player $player): void {
        Utils::getInstance()->playSound($player, "note.bell");
    }

    public function errorSound(Player $player): void {
        Utils::getInstance()->playSound($player, "note.bass");
    }
}