<?php

namespace MvcliteCore\Engine\InternalResources\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class NotFoundResourceException extends MvcLiteException
{
    public function __construct(string $fileName)
    {
        parent::__construct();

        $this->code = "MVCLITE_NOT_FOUND_RESOURCE";
        $this->message = "<strong>$fileName</strong> resource is not found.";
    }
}