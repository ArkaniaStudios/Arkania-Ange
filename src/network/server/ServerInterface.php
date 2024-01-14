<?php
declare(strict_types=1);

namespace arkania\network\server;

use arkania\Engine;
use arkania\utils\promise\PromiseInterface;

interface ServerInterface {

    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';
    const STATUS_STARTING = 'starting';
    const STATUS_STOPPING = 'stopping';
    const STATUS_RESTARTING = 'restarting';
    const STATUS_CRASHED = 'crashed';
    const STATUS_WHITELIST = 'whitelist';

    public function getId(): int;

    public function getName(): string;

    public function getIp(): string;

    public function getPort(): int;

    public function getStatus(): PromiseInterface;

    public function getEngine() : Engine;

    public function getStringStatus() : string;

    public function setStatus(string $status) : void;

}