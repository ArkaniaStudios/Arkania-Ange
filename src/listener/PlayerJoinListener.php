<?php
declare(strict_types=1);

namespace arkania\listener;

use arkania\events\EngineListener;
use arkania\player\Session;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoinListener implements EngineListener {

    public function onPlayerJoin(PlayerJoinEvent $event) : void {
        Session::create($event->getPlayer());
    }

}