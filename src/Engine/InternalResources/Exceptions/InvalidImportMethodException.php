<?php

namespace MvcliteCore\Engine\InternalResources\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class InvalidImportMethodException extends MvcLiteException
{
    public function __construct()
    {
        parent::__construct();

        $this->code = "MVCLITE_INVALID_IMPORTED_METHOD";
        $this->message = "Given import method does not longer exist.";
    }
}