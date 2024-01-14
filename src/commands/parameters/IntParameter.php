<?php
declare(strict_types=1);

namespace arkania\commands\parameters;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class IntParameter extends Parameter {

    private bool $acceptNegative;

    public function __construct(
        string $name,
        bool $acceptNegative = false,
        bool $isOptional = false
    ) {
        parent::__construct($name, $isOptional);
        $this->acceptNegative = $acceptNegative;
    }

    public function getNetworkType() : int {
        return AvailableCommandsPacket::ARG_TYPE_INT;
    }

    public function canParse(string $argument, CommandSender $sender) : bool {
        return (bool) preg_match(
            "/^" . ($this->acceptNegative ? "-?" : "") . "[0-9]+$/",
            $argument
        );
    }

    public function parse(string $argument, CommandSender $sender) : int {
        return (int) $argument;
    }

}