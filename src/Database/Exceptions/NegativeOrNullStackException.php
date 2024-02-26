<?php

namespace MvcliteCore\Database\Exceptions;

use MvcliteCore\Database\Pagination;
use MvcliteCore\Engine\MvcLiteException;

/**
 * MVCLite core exception.
 *
 * This exception must be thrown if pagination is created
 * for database results using a negative or null stacking.
 *
 * @see Pagination
 * @author belicfr
 */
class NegativeOrNullStackException extends MvcLiteException
{
    public function __construct()
    {
        parent::__construct();

        $this->code = "MVCLITE_NEGATIVE_OR_NULL_DATABASE_STACKING";
        $this->message = "Database stacking requires a positive integer (>= 1).";
    }
}