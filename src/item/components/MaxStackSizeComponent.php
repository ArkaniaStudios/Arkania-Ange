<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

class MaxStackSizeComponent extends BaseComponent {

    private int $maxStackSize;

    public function __construct(
        int $maxStackSize = 1
    ) {
        if ($maxStackSize > 64)
            $maxStackSize = 64;
        else if ($maxStackSize < 1)
            $maxStackSize = 1;
        $this->maxStackSize = $maxStackSize;
    }

    public function getComponentName(): string {
        return 'max_stack_size';
    }

    public function getValue(): int {
        return $this->maxStackSize;
    }

    public function isProperty(): bool {
        return true;
    }

}