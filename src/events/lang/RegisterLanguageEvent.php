<?php
declare(strict_types=1);

namespace arkania\events\lang;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\lang\Language;

class RegisterLanguageEvent extends Event implements Cancellable {
    use CancellableTrait;

    private Language $language;

    public function __construct(
        Language $language
    ) {
        $this->language = $language;
    }

    public function getLanguage() : Language {
        return $this->language;
    }

}