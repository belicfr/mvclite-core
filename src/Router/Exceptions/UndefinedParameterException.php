<?php

namespace MvcliteCore\Router\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class UndefinedParameterException extends MvcLiteException
{
    public function __construct(string $parameterKey)
    {
        parent::__construct();

        $this->code = "MVCLITE_UNDEFINED_PARAMETER";
        $this->message = "$parameterKey parameter does not longer exist.";
    }
}