<?php
declare(strict_types=1);

namespace arkania\commands\listener;

use arkania\commands\CommandBase;
use arkania\commands\EnumStore;
use arkania\commands\parameters\SubParameter;
use arkania\Engine;
use arkania\libs\muqsit\simplepackethandler\SimplePacketHandler;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

class CommandDataListener {

    private Engine $engine;
    private static bool $isIntercepting = false;

    public function __construct(
        Engine $engine
    ) {
        $this->engine = $engine;
        $this->init();
    }

    private function init() : void {
        $interceptor = SimplePacketHandler::createInterceptor($this->engine);
        $interceptor->interceptOutgoing(
            function (AvailableCommandsPacket $packet, NetworkSession $session) : bool {
                if (self::$isIntercepting) {
                    return true;
                }
                $player     = $session->getPlayer();
                $commandMap = $this->engine->getServer()->getCommandMap();
                foreach ($packet->commandData as $commandName => $commandData) {
                    $command = $commandMap->getCommand($commandName);
                    if ($command instanceof CommandBase) {
                        $packet->commandData[$commandName]->overloads = self::generateOverloads($player, $command);
                    }
                }
                $packet->softEnums    = EnumStore::getEnums();
                self::$isIntercepting = true;
                $session->sendDataPacket($packet);
                self::$isIntercepting = false;
                return false;
            }
        );
    }

    /**
     * @return CommandOverload[][]
     */
    private static function generateOverloads(CommandSender $sender, CommandBase $command) : array {
        $overloads    = [];
        $subParameter = false;

        foreach ($command->getParameters() as $parameters) {
            foreach ($parameters as $parameter) {
                if ($parameter instanceof SubParameter) {
                    $overloads[]  = new CommandOverload(false, [$parameter->getCommandParameter()]);
                    $subParameter = true;
                }
            }
        }

        if (!$subParameter) {
            if ($command->getSubCommands() !== []) {
                foreach ($command->getSubCommands() as $label => $subCommand) {
                    if (!$subCommand->testPermissionSilent($sender) || $subCommand->getName() !== $label) { // hide aliases
                        continue;
                    }
                    $commandParameter             = new CommandParameter();
                    $commandParameter->paramName  = $label;
                    $commandParameter->paramType  = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
                    $commandParameter->isOptional = false;
                    $commandParameter->enum       = new CommandEnum($label, [$label]);

                    /** @var CommandOverload[] $overloadList */
                    $overloadList = self::generateOverloadList($subCommand);
                    if (!empty($overloadList)) {
                        foreach ($overloadList as $overload) {
                            $commandOverload = new CommandOverload(false, [$commandParameter, ...$overload->getParameters()]);
                            $overloads[]     = $commandOverload;
                        }
                    } else {
                        $commandOverload = new CommandOverload(false, [$commandParameter]);
                        $overloads[]     = $commandOverload;
                    }

                }
            }

            foreach (self::generateOverloadList($command) as $overload) {
                $overloads[] = $overload;
            }
        }
        return $overloads;
    }

    /**
     * @return array<int, CommandOverload>
     */
    private static function generateOverloadList(CommandBase $parameter) : array {
        $input        = $parameter->getParameters();
        $combinations = [];
        $outputLength = array_product(array_map("count", $input));
        $indexes      = [];
        foreach($input as $k => $charList) {
            $indexes[$k] = 0;
        }
        do {
            /** @var CommandParameter[] $set */
            $set = [];
            foreach($indexes as $k => $index) {
                $set[$k] = clone $input[$k][$index]->getCommandParameter();

            }
            $combinations[] = new CommandOverload(false, $set);

            foreach($indexes as $k => $v) {
                $indexes[$k]++;
                $lim = count($input[$k]);
                if($indexes[$k] >= $lim) {
                    $indexes[$k] = 0;
                    continue;
                }
                break;
            }
        } while(count($combinations) !== $outputLength);
        return $combinations;
    }

}