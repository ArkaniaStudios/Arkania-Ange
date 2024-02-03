<?php
declare(strict_types=1);

namespace arkania\commands\default;

use arkania\commands\CommandBase;
use arkania\commands\default\sub\MaintenanceOffSubCommand;
use arkania\commands\default\sub\MaintenanceOnSubCommand;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use pocketmine\command\CommandSender;

class MaintenanceCommand extends CommandBase {

    /**
     * @throws MissingPermissionException
     */
    public function __construct(
        Engine $engine
    ) {
        parent::__construct(
            'maintenance',
            KnownTranslationsFactory::command_maintenance_description(),
            '/maintenance <on:off>',
            [
                new MaintenanceOnSubCommand($engine),
                new MaintenanceOffSubCommand($engine)
            ]
        );
        $this->setPermission(PermissionsBase::getPermission('maintenance'));
    }

    public function getCommandParameter() : array {
        return [];
    }

    public function onRun(CommandSender $sender, array $parameters) : void {
    }

}