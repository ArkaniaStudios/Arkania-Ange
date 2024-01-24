<?php
declare(strict_types=1);

namespace arkania\listener;

use arkania\events\EngineListener;
use arkania\lang\event\PlayerChangeLanguageEvent;
use arkania\player\Session;

class PlayerChangeLanguageListener implements EngineListener {

    public function onPlayerChangeLanguage(PlayerChangeLanguageEvent $event) : void {
        Session::syncAvailableCommands($event->getPlayer());
    }

}