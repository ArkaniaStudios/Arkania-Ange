<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

trait ComponentsTrait {

    /** @var BaseComponent[} */
    protected array $components = [];


    public function addComponent(BaseComponent $component): void {
        $this->components[] = $component;
    }

    public function removeComponent(BaseComponent $component): void {
        $key = array_search($component, $this->components);
        if ($key !== false) {
            unset($this->components[$key]);
        }
    }

    public function hasComponent(BaseComponent $component): bool {
        return isset($this->components[array_search($component, $this->components)]);
    }

}