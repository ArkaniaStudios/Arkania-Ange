<?php
declare(strict_types=1);

namespace arkania\commands\default;

use arkania\commands\CommandBase;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

class VersionCommand extends CommandBase {

    /**
     * @throws MissingPermissionException
     */
    public function __construct() {
        parent::__construct(
            'version',
            KnownTranslationsFactory::command_version_description(),
            '/version',
            [],
            [
                'v'
            ]
        );
        $this->setPermission(PermissionsBase::getPermission('base'));
    }

    public function getCommandParameter() : array {
        return [];
    }

    public function onRun(CommandSender $sender, array $parameters) : void {
        if($sender instanceof Player){
            $sender = Session::get($sender);
        }
        $sender->sendMessage(
            KnownTranslationsFactory::command_version_message(
                Engine::getInstance()->getServerName(),
                Engine::getInstance()->getApiVersion(),
                PHP_VERSION,
                Utils::getOS(),
                ProtocolInfo::MINECRAFT_VERSION_NETWORK
            )
        );
    }

}