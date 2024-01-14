<?php
declare(strict_types=1);

namespace arkania\commands\parameters;

use arkania\commands\EnumStore;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use RuntimeException;

class SubParameter extends Parameter {

    public function __construct(
        string $name,
        string $subCommandType,
        bool $isOptional = false
    ) {
        parent::__construct($name, $isOptional);
        $this->getCommandParameter()->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
        EnumStore::addEnum($this->getCommandParameter()->enum = new CommandEnum(strtolower($name), [strtolower($subCommandType)]));
    }

    public function getNetworkType() : int {
        return CommandParameter::FLAG_FORCE_COLLAPSE_ENUM;
    }

    public function canParse(string $argument, CommandSender $sender) : bool {
        return true;
    }

    public function parse(string $argument, CommandSender $sender) : mixed {
        throw new RuntimeException("SubParameter cannot be parsed");
    }

}