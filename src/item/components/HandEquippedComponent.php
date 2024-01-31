<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

class HandEquippedComponent extends BaseComponent {

    private bool $handEquipped;

    public function __construct(bool $handEquipped = true) {
        $this->handEquipped = $handEquipped;
    }

    public function getComponentName(): string {
        return "hand_equipped";
    }

    public function getValue(): bool {
        return $this->handEquipped;
    }

    public function isProperty(): bool {
        return true;
    }


}