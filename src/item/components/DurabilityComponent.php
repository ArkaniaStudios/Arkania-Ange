<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

class DurabilityComponent extends BaseComponent {

    private int $maxDurability;

    public function __construct(int $maxDurability) {
        $this->maxDurability = $maxDurability;
    }

    public function getComponentName(): string {
        return "minecraft:durability";
    }

    public function getValue(): array {
        return [
            "max_durability" => $this->maxDurability
        ];
    }

    public function isProperty(): bool {
        return false;
    }


}