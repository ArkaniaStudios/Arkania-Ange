<?php
declare(strict_types=1);

namespace arkania\commands\parameters;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class TextParameter extends StringParameter {

    public function getNetworkType() : int {
        return AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
    }

    public function getSpanLength() : int {
        return PHP_INT_MAX;
    }

    public function canParse(string $argument, CommandSender $sender) : bool {
        return $argument !== '';
    }
}