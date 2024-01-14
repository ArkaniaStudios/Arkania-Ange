<?php
declare(strict_types=1);

namespace arkania\listener;

use arkania\events\EngineListener;
use arkania\player\Session;
use pocketmine\event\player\PlayerLoginEvent;

class PlayerLoginListener implements EngineListener {

    public function onPlayerLogin(PlayerLoginEvent $event) : void {
        Session::create($event->getPlayer());
    }

}