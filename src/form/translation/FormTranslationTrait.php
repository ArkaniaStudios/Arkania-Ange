<?php
declare(strict_types=1);

namespace arkania\form\translation;

use arkania\player\Session;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

trait FormTranslationTrait {

    private Player $player;

    public function __construct(
        Player $player
    ) {
        $this->player = $player;
    }

    public function translate(Translatable|string $translatable) : string {
        if($translatable instanceof Translatable) {
            $translatable = Session::get($this->player)->getLanguage()->translate($translatable);
        }
        return $translatable;
    }

}