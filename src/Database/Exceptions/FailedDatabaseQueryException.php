<?php

namespace MvcliteCore\Database\Exceptions;

use MvcliteCore\Database\Database;
use MvcliteCore\Engine\MvcLiteException;

/**
 * MVCLite core exception.
 *
 * This exception must be thrown if run SQL query
 * failed, for a syntax error, for example.
 *
 * @see Database
 * @author belicfr
 */
class FailedDatabaseQueryException extends MvcLiteException
{
    public function __construct(string $query)
    {
        parent::__construct();

        $this->code = "MVCLITE_DB_FAILED_QUERY";
        $this->message = "<strong>Database Error:</strong> Failed to run database query.<br />
                          <strong>Query:</strong> $query";
    }
}