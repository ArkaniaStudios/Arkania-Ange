<?php
declare(strict_types=1);

namespace arkania\database;

use Logger;
use React\Promise\PromiseInterface;

interface DataConnector {

    public function setLoggingQueries(bool $loggingQueries) : void;

    public function isLoggingQueries() : bool;

    public function setLogger(?Logger $logger) : void;

    public function getLogger() : ?Logger;

    public function executeGeneric(string $query, array $params = []) : PromiseInterface;

    public function executeInsert(string $query, array $params = []) : PromiseInterface;

    public function executeChange(string $query, array $params = []) : PromiseInterface;

    public function executeSelect(string $query, array $params = []) : PromiseInterface;

    public function waitAll() : void;

    public function close() : void;

}