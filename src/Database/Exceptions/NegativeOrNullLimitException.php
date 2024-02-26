<?php

namespace MvcliteCore\Database\Exceptions;

use MvcliteCore\Database\ORM\ORMSelection;
use MvcliteCore\Engine\MvcLiteException;

/**
 * MVCLite core exception.
 *
 * This exception must be thrown if query has
 * limit defined with negative or null value (<= 0).
 *
 * @see ORMSelection
 * @author belicfr
 */
class NegativeOrNullLimitException extends MvcLiteException
{
    public function __construct(string $query)
    {
        parent::__construct();

        $this->code = "MVCLITE_NEGATIVE_OR_NULL_QUERY_LIMIT";
        $this->message = "<strong>Database Error:</strong> Query limit requires 
                          a positive integer (>= 1). <br />
                          <strong>Query:</strong> $query";
    }
}