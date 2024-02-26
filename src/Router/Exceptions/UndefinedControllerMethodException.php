<?php

namespace MvcliteCore\Router\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class UndefinedControllerMethodException extends MvcLiteException
{
    public function __construct(string $controller, string $method)
    {
        parent::__construct();

        $this->code = "MVCLITE_UNDEFINED_CONTROLLER_METHOD";
        $this->message = "<strong><u>$controller</u>::$method()</strong> is undefined.";
    }
}