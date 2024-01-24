<?php
declare(strict_types=1);

namespace arkania\commands\default;

use arkania\commands\CommandBase;
use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use arkania\plugins\EnginePlugin;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PluginCommand extends CommandBase {

    public function __construct() {
        parent::__construct(
            'plugins',
            KnownTranslationsFactory::command_plugin_description(),
            '/plugins',
            [],
            [
                'pl',
                'plugin'
            ]
        );
        $this->setPermission(PermissionsBase::getPermission('base'));
    }

    public function getCommandParameter() : array {
        return [];
    }

    public function onRun(CommandSender $sender, array $parameters) : void {
        if($sender instanceof Player) {
            $sender = Session::get($sender);
        }

        $list = array_map(function(EnginePlugin $plugin) : string{
            return ($plugin->isEnabled() ? TextFormat::GREEN : TextFormat::RED) . $plugin->getInformations()->getFullName();
        }, Engine::getInstance()->getServerLoader()->getPluginManager()->getPlugins());
        sort($list, SORT_STRING);

        $sender->sendMessage(
            KnownTranslationsFactory::command_plugin_list(
                (string) count($list),
                implode(TextFormat::RESET . ", ", $list)
            )
        );
    }

}