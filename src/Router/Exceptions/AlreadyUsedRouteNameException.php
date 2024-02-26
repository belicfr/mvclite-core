<?php

namespace MvcliteCore\Router\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class AlreadyUsedRouteNameException extends MvcLiteException
{
    public function __construct(string $routeName)
    {
        parent::__construct();

        $this->code = "MVCLITE_ALREADY_USED_ROUTE_NAME";
        $this->message = "<strong>$routeName</strong> route name is already taken.";
    }
}