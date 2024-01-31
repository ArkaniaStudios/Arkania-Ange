<?php
declare(strict_types=1);

namespace arkania\form;

use arkania\form\permission\FormPermissionTrait;
use arkania\form\translation\FormTranslationTrait;
use pocketmine\form\Form;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

abstract class BaseForm implements Form {
    use FormTranslationTrait {
        FormTranslationTrait::__construct as private __formTranslationConstruct;
    }
    use FormPermissionTrait{
        FormPermissionTrait::__construct as private __formPermissionConstruct;
    }

    protected string $title;

    public function __construct(
        Player $player,
        Translatable|string $title
    ) {
        $this->__formTranslationConstruct($player);
        $this->__formPermissionConstruct($player);
        $this->player = $player;
        $this->title = $this->translate($title);
    }

    public function getTitle() : string {
        return $this->title;
    }

    abstract public function getType() : string;

}