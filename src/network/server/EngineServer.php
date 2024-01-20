<?php
declare(strict_types=1);

namespace arkania\network\server;

use arkania\Engine;
use arkania\utils\promise\PromiseInterface;

final class EngineServer implements ServerInterface {

    private Engine $engine;
    private int $id;
    private string $name;
    private string $ip;
    private int $port;
    private string $status;

    public function __construct(
        Engine $engine,
        int $id,
        string $name,
        string $ip,
        int $port,
        string $status = self::STATUS_STARTING
    ) {
        $this->engine = $engine;
        $this->id = $id;
        $this->name = $name;
        $this->ip = $ip;
        $this->port = $port;
        $this->status = $status;
        $engine->getDataBaseManager()->getConnector()->executeGeneric(
            'CREATE TABLE IF NOT EXISTS servers (
                id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                ip VARCHAR(255) NOT NULL,
                port INT NOT NULL,
                status VARCHAR(255) NOT NULL
            )'
        );
    }

    public function getName(): string {
        return $this->name;
    }

    public function getIp(): string {
        return $this->ip;
    }

    public function getPort(): int {
        return $this->port;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEngine() : Engine {
        return $this->engine;
    }

    public function getStatus() : PromiseInterface {
        return $this->engine->getDataBaseManager()->getConnector()->executeSelect(
            'SELECT status FROM servers WHERE id= ?',
            [$this->id]
        );
    }

    public function getStringStatus() : string {
        return $this->status;
    }

    public function setStatus(string $status) : void {
        $this->getEngine()->getDataBaseManager()->getConnector()->executeChange(
            'UPDATE servers SET status = ? WHERE id = ?',
            [$status, $this->id]
        );
    }
}