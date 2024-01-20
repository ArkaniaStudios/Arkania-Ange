<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

/**
 * Class AllowOffHandComponent
 * This class extends the BaseComponent class and represents a component that allows an item to be held in the offhand.
 */
class AllowOffHandComponent extends BaseComponent {

    private bool $offHand;

    public function __construct(
        bool $offHand = false
    ) {
        $this->offHand = $offHand;
    }

    public function getComponentName() : string {
        return "allow_off_hand";
    }

    public function getValue() : bool {
        return $this->offHand;
    }

    public function isProperty() : bool {
        return true;
    }

}