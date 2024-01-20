<?php
declare(strict_types=1);

namespace arkania\commands\parameters;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class StringParameter extends Parameter {

    public function getNetworkType() : int {
        return AvailableCommandsPacket::ARG_TYPE_STRING;
    }

    public function canParse(string $argument, CommandSender $sender) : bool {
        return false;
    }

    public function parse(string $argument, CommandSender $sender) : string {
        return $argument;
    }

}