<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

class CanDestroyInCreativeComponent extends BaseComponent {

    private bool $canDestroyInCreative;

    public function __construct(
        bool $canDestroyInCreative = true
    ) {
        $this->canDestroyInCreative = $canDestroyInCreative;
    }

    public function getComponentName(): string {
        return "can_destroy_in_creative";
    }

    public function getValue(): bool {
        return $this->canDestroyInCreative;
    }

    public function isProperty(): bool {
        return true;
    }

}