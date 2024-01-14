<?php
declare(strict_types=1);

namespace arkania\network\server;

use arkania\database\result\SqlSelectResult;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use arkania\webhook\AlreadyRegisteredException;

class ServerManager implements NotOtherInstanceInterface {
    use NotOtherInstanceTrait {
        NotOtherInstanceTrait::__construct as traitConstruct;
    }

    /** @var ServerInterface[] */
    private array $servers = [];

    public function __construct() {
        $this->traitConstruct();
    }

    /**
     * @throws AlreadyRegisteredException
     */
    public function addServer(ServerInterface $server) : void {
        if(isset($this->servers[$server->getId()])){
            throw new AlreadyRegisteredException('Server with id ' . $server->getId() . ' is already registered');
        }
        ServersIds::addServer($server->getPort(), $server->getId());
        $server->getEngine()->getDataBaseManager()->getConnector()->executeSelect(
            'SELECT * FROM servers WHERE id = ?',
            [$server->getId()]
        )->then(function(SqlSelectResult $result) use ($server): void {
            if(count($result->getRows()) === 0) {
                $server->getEngine()->getDataBaseManager()->getConnector()->executeInsert(
                    'INSERT INTO servers (id, name, ip, port, status) VALUES (?, ?, ?, ?, ?)',
                    [$server->getId(), $server->getName(), $server->getIp(), $server->getPort(), ServerInterface::STATUS_ONLINE]
                );
            }else{
                $server->getEngine()->getDataBaseManager()->getConnector()->executeChange(
                    'UPDATE servers SET name = ?, ip = ?, port = ?, status = ? WHERE id = ?',
                    [$server->getName(), $server->getIp(), $server->getPort(), $server->getStringStatus(), $server->getId()]
                );
            }
        });
        $this->servers[$server->getId()] = $server;
    }

    public function getServer(int $id) : ?ServerInterface {
        return $this->servers[$id] ?? null;
    }

    public function getServers() : array {
        return $this->servers;
    }

    public function removeServer(int $id) : void {
        unset($this->servers[$id]);
    }

    public function removeAllServers() : void {
        $this->servers = [];
    }

}