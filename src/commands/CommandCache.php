<?php
declare(strict_types=1);

namespace arkania\commands;

use arkania\Engine;
use pocketmine\command\CommandMap;

class CommandCache {

    private CommandMap $commandMap;

    public function __construct(
        readonly Engine $engine
    ) {
        $this->commandMap = $engine->getServer()->getCommandMap();
    }

    public function registerCommands(CommandBase ...$commands) : void {
        $this->commandMap->registerAll('ArkaniaStudios', $commands);
    }
    public function unregisterCommands(string ...$string) : void {
        foreach ($string as $command) {
            $this->commandMap->unregister($this->commandMap->getCommand($command));
        }
    }

}