<?php
declare(strict_types=1);

namespace arkania\form\element\elements;

class Input extends Element {

    private string $text;
    private string $placeholder;
    private string $default;

    public function __construct(
        string $name,
        string $text = '',
        ?string $placeholder = null,
        ?string $default = null
    ) {
        parent::__construct($name);
        $this->text = $text;
        $this->placeholder = $placeholder;
        $this->default = $default;
    }

    public function getType() : string {
        return 'input';
    }

    public function handler($data) : bool|string|int {
        return $data;
    }

    public function jsonSerialize() : array {
        return [
            'type' => $this->getType(),
            'text' => $this->text,
            'placeholder' => $this->placeholder,
            'default' => $this->default
        ];
    }

}