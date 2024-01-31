<?php
declare(strict_types=1);

namespace arkania\lang\event;

use arkania\lang\Language;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerChangeLanguageEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    public function __construct(
        Player $player,
        private readonly Language $language
    ) {
        $this->player = $player;
    }

    public function getLanguage() : Language {
        return $this->language;
    }

}