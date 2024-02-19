<?php
declare(strict_types=1);

namespace arkania\commands;

use arkania\commands\parameters\Parameter;
use MongoDB\Driver\Exception\CommandException;
use Throwable;

class InvalidCommandSyntax extends CommandException {

    private Parameter $parameter;

    public function __construct(
        Parameter $parameter,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->parameter = $parameter;
        parent::__construct($message, $code, $previous);
    }

    public function getParameter() : Parameter{
        return $this->parameter;
    }

}