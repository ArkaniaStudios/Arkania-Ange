<?php
declare(strict_types=1);

namespace arkania\commands\parameters;

use pocketmine\command\CommandSender;

class LanguageParameter extends EnumParameter {

    public function __construct(
        string $name,
        string $enumName,
        bool $isOptional = false
    ) {
        $this->addValue('french', 'french');
        $this->addValue('english', 'english');
        parent::__construct($name, $enumName, $isOptional);
    }

    public function parse(string $argument, CommandSender $sender) : string {
        return $argument;
    }

}