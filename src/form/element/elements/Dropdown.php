<?php
declare(strict_types=1);

namespace arkania\form\element\elements;

class Dropdown extends Element {

    private array $options;

    public function __construct(
        string $name,
        array $options = [],
    ) {
        parent::__construct($name);
        $this->options = $options;
    }

    public function getType() : string {
        return 'dropdown';
    }

    public function handler($data) : bool|string|int {
        return $this->options[$data];
    }

    /**
     * @return array<string, string|array<string, string>>
     */
    public function jsonSerialize() : array {
        return [
            'type' => $this->getType(),
            'text' => $this->getName(),
            'options' => $this->options
        ];
    }

}