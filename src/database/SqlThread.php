<?php
declare(strict_types=1);

namespace arkania\database;

interface SqlThread {

    public const MODE_GENERIC = 0;
    public const MODE_CHANGE = 1;
    public const MODE_INSERT = 2;
    public const MODE_SELECT = 3;

    /**
     * @see https://php.net/thread.join Thread::join
     */
    public function join();

    public function stopRunning() : void;

    /**
     * @param int $queryId
     * @param int $modes
     * @param string $queries
     * @param mixed[] $params
     * @return void
     */
    public function addQuery(int $queryId, int $modes, string $queries, array $params) : void;

    /**
     * @param callable[] $callbacks
     * @param int|null $expectedResults
     * @return void
     */
    public function readResults(array &$callbacks, ?int $expectedResults) : void;

    public function connCreated() : bool;

    public function hasConnError() : bool;

    public function getConnError() : ?string;


}