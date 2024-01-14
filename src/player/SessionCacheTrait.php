<?php
declare(strict_types=1);

namespace arkania\player;

use arkania\database\result\SqlSelectResult;
use arkania\Engine;
use pocketmine\player\Player;
use WeakMap;

trait SessionCacheTrait {

    /**
     * @var WeakMap
     * @phpstan-var WeakMap<Player, Session>
     */
    private static WeakMap $data;

    public static function get(Player $player) : self {
        if(!isset(self::$data)){
            $data = new WeakMap();
            self::$data = $data;
        }
        return self::$data[$player] ?? self::loadSession($player);
    }

    private static function loadSession(Player $player) : self {
        return new Session($player->getNetworkSession());
    }

    public static function create(Player $player) : void {
        Engine::getInstance()->getDataBaseManager()->getConnector()->executeGeneric(
            'CREATE TABLE IF NOT EXISTS players(
                uuid VARCHAR(36) NOT NULL,
                language VARCHAR(20) NOT NULL,
                permissions TEXT NOT NULL,
                last_ip VARCHAR(255) NOT NULL,
                last_login BIGINT NOT NULL,
                last_logout BIGINT NOT NULL,
                first_login BIGINT NOT NULL,
                play_time BIGINT NOT NULL,
                play_time_today BIGINT NOT NULL,
                `rank` VARCHAR(32) DEFAULT NULL,
                rank_expiration BIGINT DEFAULT NULL
            )')->then(function() use ($player){
            Engine::getInstance()->getDataBaseManager()->getConnector()->executeSelect(
                'SELECT * FROM players WHERE uuid = ?',
                [
                    $player->getUniqueId()->__toString()
                ]
            )->then(function(SqlSelectResult $result) use ($player){
                if (count($result->getRows()) <= 0){
                    Engine::getInstance()->getDataBaseManager()->getConnector()->executeInsert(
                        'INSERT INTO players(
                uuid,
                language,
                permissions,
                last_ip,
                last_login,
                last_logout,
                first_login,
                play_time,
                play_time_today,
                `rank`,
                rank_expiration
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?)',
                        [
                            $player->getUniqueId()->toString(),
                            'french',
                            serialize([]),
                            $player->getNetworkSession()->getIp(),
                            time(),
                            0,
                            time(),
                            0,
                            0,
                            'Joueur',
                            -1
                        ]
                    );
                }
            });
        });
        self::syncAvailableCommands($player);
        self::get($player);
    }

}