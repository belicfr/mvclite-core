<?php

namespace MvcliteCore\Database\Exceptions;

use MvcliteCore\Database\Database;
use MvcliteCore\Engine\MvcLiteException;

/**
 * MVCLite core exception.
 *
 * This exception must be thrown if a non-existent column
 * in a SQL query using Database class.
 *
 * @see Database::query()
 * @author belicfr
 */
class ColumnNotFoundException extends MvcLiteException
{
    public function __construct(string $column, string $query)
    {
        parent::__construct();

        $this->code = "MVCLITE_DB_COLUMN_NOT_FOUND";
        $this->message = "<strong>Database Error:</strong> $column column does not exist.<br />
                          <strong>Query:</strong> $query";
    }
}