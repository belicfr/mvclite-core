<?php

namespace MvcliteCore\API\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class NotFoundApiException extends MvcLiteException
{
    public function __construct(string $apiName)
    {
        parent::__construct();

        $this->code = "MVCLITE_NOT_FOUND_API";
        $this->message = "$apiName API not found.";
    }
}