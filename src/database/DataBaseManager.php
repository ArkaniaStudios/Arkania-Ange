<?php
declare(strict_types=1);

namespace arkania\database;

use arkania\database\base\DataConnectorImpl;
use arkania\database\base\SqlThreadPool;
use arkania\database\mysqli\MysqlCredentials;
use arkania\database\mysqli\MysqliThread;
use arkania\Engine;
use arkania\utils\AlreadyInstantiatedException;
use arkania\utils\BadExtensionException;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use BadFunctionCallException;

final class DataBaseManager implements NotOtherInstanceInterface {
    use NotOtherInstanceTrait{
        NotOtherInstanceTrait::__construct as private __notOtherInstanceConstruct;
    }

    private DataConnectorImpl $connector;

    /**
     * @throws BadExtensionException
     * @throws AlreadyInstantiatedException
     */
    public function __construct(
        private readonly Engine $plugin
    ) {
        if(!extension_loaded("mysqli")){
            throw new BadFunctionCallException("The mysqli extension is not loaded");
        }
        $this->__notOtherInstanceConstruct();
        $cred = MysqlCredentials::fromArray(
            $this->plugin->getConfig()->get("database")
        );
        $factory = MysqliThread::createFactory($cred, $this->plugin->getServer()->getLogger());
        $pool = new SqlThreadPool($factory, 1);
        while(!$pool->connCreated()){
            usleep(1000);
        }
        if($pool->hasConnError()) {
            throw new SqlError(SqlError::STAGE_CONNECT, $pool->getConnError());
        }
        $this->connector = new DataConnectorImpl(
            $this->plugin,
            $pool
        );
    }

    public function getConnector() : DataConnectorImpl {
        return $this->connector;
    }

}