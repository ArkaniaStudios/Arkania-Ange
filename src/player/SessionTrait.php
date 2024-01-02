<?php
declare(strict_types=1);

namespace arkania\player;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\player\Player;
use WeakMap;

trait SessionTrait {

    /**
     * @var WeakMap
     * @phpstan-var WeakMap<Player, Session>
     */
    private static WeakMap $session;

    private NetworkSession $networkSession;

    public static function get(Player $player) : Session {
        if(!isset(self::$session)) {
            self::$session = new WeakMap();
        }
        return self::$session[$player] ??= self::createSession($player->getNetworkSession());
    }

    private static function createSession(NetworkSession $session) : Session {
        return new Session(
            $session,
            $session->getPlayer()->getUniqueId()->toString()
        );
    }

    public function __construct(
        NetworkSession          $session,
        private readonly string $id,
    ) {
        $this->networkSession = $session;
    }

    public function getId() : string {
        return $this->id;
    }

    public function getNetworkSession() : NetworkSession {
        return $this->networkSession;
    }

    public function getPlayer() : Player {
        return $this->networkSession->getPlayer();
    }

}