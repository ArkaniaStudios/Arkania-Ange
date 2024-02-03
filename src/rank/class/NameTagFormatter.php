<?php
declare(strict_types=1);

namespace arkania\rank\class;

class NameTagFormatter {

    private string $format;

    public function __construct(
        string $format
    ) {
        $this->format = $format;
    }

    public function getFormat(): string {
        return $this->format;
    }

}