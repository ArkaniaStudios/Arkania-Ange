<?php
declare(strict_types=1);

namespace arkania\form\element\elements;

class Label extends Element {

    private string $text;

    public function __construct(
        string $name,
        string $text
    ) {
        parent::__construct($name);
        $this->text = $text;
    }

    public function getType() : string {
        return "label";
    }

    public function handler($data) : bool|string|int {
        return $this->text;
    }

    public function jsonSerialize() : array {
        return [
            "type" => $this->getType(),
            "text" => $this->text
        ];
    }

}