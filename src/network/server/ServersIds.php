<?php
declare(strict_types=1);

namespace arkania\network\server;

use InvalidArgumentException;

final class ServersIds {

    private static array $serversPort = [];

    private static array $serversIds = [];

    public static function addServer(int $port, int $serverId) : void {
        self::$serversPort[$port] = $serverId;
        self::$serversIds[$serverId] = $port;
    }

    public static function getIdWithPort(int $port) : int {
        return self::$serversPort[$port] ?? throw new InvalidArgumentException("Invalid port $port you can use `addServer`");
    }

    public static function getPortWithId(string $id) : int {
        return self::$serversIds[$id] ?? throw new InvalidArgumentException("Invalid server name $id you can use `addServer`");
    }

}