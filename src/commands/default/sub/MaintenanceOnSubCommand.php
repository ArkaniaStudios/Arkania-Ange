<?php
declare(strict_types=1);

namespace arkania\commands\default\sub;

use arkania\commands\CommandBase;
use arkania\database\result\SqlSelectResult;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\network\server\ServersIds;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class MaintenanceOnSubCommand extends CommandBase {

    /**
     * @throws MissingPermissionException
     */
    public function __construct(
        private readonly Engine $engine
    ) {
        parent::__construct(
            'on'
        );
        $this->setPermission(PermissionsBase::getPermission('maintenance'));
    }

    public function getCommandParameter() : array {
        return [
            //TODO: Implémenter le système de maintenance depuis un autre serveur.
        ];
    }

    public function onRun(CommandSender $sender, array $parameters) : void {
        if($sender instanceof Player){
            $sender = Session::get($sender);
        }
        $server = $this->engine->getServerManager()->getServer(ServersIds::getIdWithPort($this->engine->getServer()->getPort()));
        if($server === null) {
            $sender->sendMessage(KnownTranslationsFactory::command_maintenance_error());
            return;
        }

        $server->getStatus()->then(
            function (SqlSelectResult $result) use ($sender) : void {
                if (count($result->getRows()) <= 0) {
                    //wtf impossible
                    return;
                }
                if($result->getRows()[0]['maintenance'] === '1') {
                    $sender->sendMessage(KnownTranslationsFactory::command_maintenance_already_on());
                    return;
                }

                $this->engine->getDataBaseManager()->getConnector()->executeChange(
                    'UPDATE servers SET maintenance = 1 WHERE id = ?',
                    [
                        $result->getRows()[0]['id']
                    ]
                )->then(
                    function () use ($sender) : void {
                        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                            $player->disconnect(KnownTranslationsFactory::command_maintenance_disconnect());
                        }
                        $sender->sendMessage(KnownTranslationsFactory::command_maintenance_on());
                    }
                );
            }
        );
    }
}