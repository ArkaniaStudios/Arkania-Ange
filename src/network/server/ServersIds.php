<?php
declare(strict_types=1);

namespace arkania\network\server;

use InvalidArgumentException;

final class ServersIds {

    private static array $serversPort = [];

    private static array $serversIds = [];

    private static array $serversName = [];

    public static function addServer(int $port, int $serverId, string $name) : void {
        self::$serversPort[$port] = $serverId;
        self::$serversIds[$serverId] = $port;
        self::$serversName[$name] = $serverId;
    }

    public static function getIdWithPort(int $port) : int {
        return self::$serversPort[$port] ?? throw new InvalidArgumentException("Invalid port $port you can use `addServer`");
    }

    public static function getServerWithName(string $name) : int {
        return self::$serversName[$name] ?? throw new InvalidArgumentException("Invalid server name $name you can use `addServer`");
    }

    public static function getPortWithId(string $id) : int {
        return self::$serversIds[$id] ?? throw new InvalidArgumentException("Invalid server name $id you can use `addServer`");
    }

}