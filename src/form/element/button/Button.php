<?php
declare(strict_types=1);

namespace arkania\form\element\button;

use arkania\form\permission\FormPermissionTrait;
use arkania\form\translation\FormTranslationTrait;
use JsonSerializable;
use pocketmine\lang\Translatable;

class Button implements JsonSerializable {
    use FormTranslationTrait;
    use FormPermissionTrait;

    private string $name;
    private Translatable|string $text;
    private IconUrl $icon;

    public function __construct(
        string $name,
        Translatable|string $text,
        string $permission = null,
        IconUrl $icon = null
    ) {
        $this->name = $name;
        $this->text = $this->translate($text);
        $this->icon = $icon;
        if($permission !== null) {
            $this->setPermission($permission);
        }
    }

    public function getName() : string {
        return $this->name;
    }

    /**
     * @return array<string,string|string[]>
     */
    public function jsonSerialize() : array {
        return [
            'text' => $this->text,
            'image' => $this->icon->jsonSerialize() ?? null
        ];
    }

}