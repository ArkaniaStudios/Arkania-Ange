<?php
declare(strict_types=1);

namespace arkania\player;

use arkania\Engine;
use arkania\lang\event\PlayerChangeLanguageEvent;
use arkania\lang\Language;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\player\Player;
use pocketmine\Server;

class Session {
    use SessionCacheTrait;

    private Language $language;
    private NetworkSession $networkSession;

    private ?string $lastPlayerMessage = null;

    public function __construct(NetworkSession $session) {
        $this->networkSession = $session;
    }

    public function getName(): string {
        return $this->networkSession->getPlayer()->getName();
    }

    public function setLanguage(Language $language) : void {
        $ev = new PlayerChangeLanguageEvent(
            $this->networkSession->getPlayer(),
            $language
        );
        $ev->call();
        if($ev->isCancelled()) {
            return;
        }
        Engine::getInstance()->getDataBaseManager()->getConnector()->executeGeneric(
            'UPDATE players SET language = ? WHERE uuid = ?',
            [
                $language->getName(),
                $this->networkSession->getPlayer()->getUniqueId()->__toString()
            ]
        );
        $this->language = $language;
    }

    public function getLanguage() : Language {
        return $this->language ?? Engine::getInstance()->getLanguageManager()->getDefaultLanguage();
    }

    public function sendMessage(Translatable|string $message) : void {
        if($message instanceof Translatable) {
            $message = $this->getLanguage()->translate($message);
        }
        $this->networkSession->onChatMessage($message);
    }

    public function getLastPlayerMessage() : ?string {
        return $this->lastPlayerMessage;
    }

    public function setLastPlayerMessage(string $message) : void {
        $this->lastPlayerMessage = $message;
    }

    public static function syncAvailableCommands(Player $player) : void{
        $playerSession = self::get($player);
        $commandData = [];
        foreach(Server::getInstance()->getCommandMap()->getCommands() as $name => $command){
            if(isset($commandData[$command->getLabel()]) || $command->getLabel() === "help" || !$command->testPermissionSilent($player)){
                continue;
            }

            $lname = strtolower($command->getLabel());
            $aliases = $command->getAliases();
            $aliasObj = null;
            if(count($aliases) > 0){
                if(!in_array($lname, $aliases, true)){
                    //work around a client bug which makes the original name not show when aliases are used
                    $aliases[] = $lname;
                }
                $aliasObj = new CommandEnum(ucfirst($command->getLabel()) . "Aliases", array_values($aliases));
            }

            $description = $command->getDescription();
            $data = new CommandData(
                strtolower($lname),
                $description instanceof Translatable ? $playerSession->getLanguage()->translate($description) : $description,
                0,
                0,
                $aliasObj,
                [
                    new CommandOverload(chaining: false, parameters: [CommandParameter::standard("args", AvailableCommandsPacket::ARG_TYPE_RAWTEXT, 0, true)])
                ],
                chainedSubCommandData: []
            );

            $commandData[$command->getLabel()] = $data;
        }

        $player->getNetworkSession()->sendDataPacket(AvailableCommandsPacket::create($commandData, [], [], []));
    }

}