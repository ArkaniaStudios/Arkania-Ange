<?php
declare(strict_types=1);

namespace arkania\database\result;

use arkania\database\SqlResult;

class SqlChangeResult extends SqlResult {
    private int $affectedRows;

    public function __construct(int $affectedRows) {
        $this->affectedRows = $affectedRows;
    }

    public function getAffectedRows() : int {
        return $this->affectedRows;
    }
}