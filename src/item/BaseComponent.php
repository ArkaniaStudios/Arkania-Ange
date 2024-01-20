<?php
declare(strict_types=1);

namespace arkania\item;

abstract class BaseComponent {

    abstract public function getComponentName() : string;
    abstract public function getValue() : mixed;
    abstract public function isProperty(): bool;

}