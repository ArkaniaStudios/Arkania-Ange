<?php
declare(strict_types=1);

namespace arkania\commands\parameters;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerParameter extends Parameter {

    public function getNetworkType() : int {
        return AvailableCommandsPacket::ARG_TYPE_TARGET;
    }

    public function canParse(string $argument, CommandSender $sender) : bool {
        if(Server::getInstance()->getPlayerExact($argument) !== null) {
            return true;
        }
        return false;
    }

    public function parse(string $argument, CommandSender $sender) : Player {
        return Server::getInstance()->getPlayerExact($argument);
    }

}