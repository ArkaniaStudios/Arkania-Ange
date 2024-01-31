<?php
declare(strict_types=1);

namespace arkania\form\element\elements;

class StepSlider extends Element {

    private string $text;
    private array $steps;
    private int $defaultStep;

    public function __construct(
        string $name,
        string $text,
        array $steps,
        int $defaultStep = 0
    ) {
        parent::__construct($name);
        $this->text = $text;
        $this->steps = $steps;
        $this->defaultStep = $defaultStep;
    }

    public function getType() : string {
        return "step_slider";
    }

    public function handler($data) : bool|string|int {
        return $this->steps[$data];
    }

    public function jsonSerialize() : array {
        return [
            "type" => $this->getType(),
            "text" => $this->text,
            "steps" => $this->steps,
            "default" => $this->defaultStep
        ];
    }

}