<?php
declare(strict_types=1);

namespace arkania\commands\parameters;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;

abstract class EnumParameter extends Parameter {

    protected array $values = [];

    public function __construct(
        string $name,
        string $enumName,
        bool $isOptional = false
    ) {
        parent::__construct($name, $isOptional);
        $this->getCommandParameter()->enum = new CommandEnum($enumName, $this->getEnumValues());
    }

    public function getNetworkType() : int {
        return -1;
    }

    public function canParse(string $argument, CommandSender $sender) : bool {
        return (bool) preg_match(
            "/^(" . implode("|", array_map('\\strtolower', $this->getEnumValues())) . ")$/iu",
            $argument
        );
    }

    public function addValue(string $string, bool|float|int|string $value) : void {
        $this->values[strtolower($string)] = $value;
    }

    /**
     * @return string[]|bool[]|int[]|float[]
     */
    public function getEnumValues() : array {
        return array_keys($this->values);
    }

}