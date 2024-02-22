<?php

namespace arkania\utils;

use pocketmine\player\Player;

class SoundsList {

    public function validSound(Player $player): void {
        Utils::getInstance()->playSound($player, "note.bell");
    }

    public function errorSound(Player $player): void {
        Utils::getInstance()->playSound($player, "note.bass");
    }
}