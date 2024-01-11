<?php
declare(strict_types=1);

namespace arkania\utils\promise\exception;

use Exception;

class CompositeException extends Exception {

    private array $throwables;
    public function __construct(array $throwables, $message = '', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->throwables = $throwables;
    }

    /**
     * @return \Throwable[]
     */
    public function getThrowables(): array
    {
        return $this->throwables;
    }

}