<?php

namespace MvcliteCore\Views\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class NotFoundViewException extends MvcLiteException
{
    public function __construct()
    {
        parent::__construct();

        $this->code = "MVCLITE_NO_LINKED_VIEW";
        $this->message = "No one view is located to given path.";
    }
}