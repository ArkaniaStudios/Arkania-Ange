<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

/**
 * @description Permet de définir le nom visible par les joueurs. (Veuillez utiliser de préférence le pack).
 * @deprecated
 */

class DisplayNameComponent extends BaseComponent {

    private string $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }

    public function getComponentName(): string {
        return "minecraft:display_name";
    }

    public function getValue(): array {
        return [
            "value" => $this->name
        ];
    }

    public function isProperty(): bool {
        return false;
    }

}