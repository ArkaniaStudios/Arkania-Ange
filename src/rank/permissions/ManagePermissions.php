<?php
declare(strict_types=1);

namespace arkania\rank\permissions;

use arkania\player\Session;
use pocketmine\player\Player;

class ManagePermissions {

    public function updatePlayerPermissions(Player $player) : void {
        $session = Session::get($player);
    }

}