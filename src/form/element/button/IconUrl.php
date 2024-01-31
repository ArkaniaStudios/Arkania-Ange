<?php
declare(strict_types=1);

namespace arkania\form\element\button;

use JsonSerializable;

class IconUrl implements JsonSerializable {

    private string $type;
    private string $data;

    public function __construct(
        string $type,
        string $data
    ) {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize() : array {
        return [
            "type" => $this->type,
            "data" => $this->data
        ];
    }

}