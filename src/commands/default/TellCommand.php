<?php
declare(strict_types=1);

namespace arkania\commands\default;

use arkania\commands\CommandBase;
use arkania\commands\parameters\PlayerParameter;
use arkania\commands\parameters\TextParameter;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\Session;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TellCommand extends CommandBase {

    /**
     * @throws MissingPermissionException
     */
    public function __construct() {
        parent::__construct(
            'tell',
            KnownTranslationsFactory::command_tell_description(),
            '/tell <player> <message>',
            [],
            [
                'msg',
                'w',
                'whisper',
                'm'
            ]
        );
        $this->setPermission(PermissionsBase::getPermission('base'));
    }

    public function getCommandParameter() : array {
        return [
            new PlayerParameter(
                'target'
            ),
            new TextParameter(
                'message'
            )
        ];
    }

    public function onRun(CommandSender $sender, array $parameters) : void {

        if($sender instanceof Player) {
            $sender = Session::get($sender);
        }

        $target = $parameters['target'];
        $message = $parameters['message'];

        if(!$target instanceof Player) {
            $sender->sendMessage(KnownTranslationsFactory::player_not_found(
                $target
            ));
            return;
        }
        $target = Session::get($target);

        $sender->sendMessage(KnownTranslationsFactory::command_tell_message_sent(
            $target->getName(),
            $message
        ));
        $target->sendMessage(KnownTranslationsFactory::command_tell_message_received(
            $sender->getName(),
            $message
        ));

        $target->setLastPlayerMessage($sender->getName());
        $sender->setLastPlayerMessage($target->getName());

    }
}