<?php

namespace MvcliteCore\Plugins\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class NotFoundPluginException extends MvcLiteException
{
    public function __construct(string $plugin)
    {
        parent::__construct();

        $this->code = "MVCLITE_NOT_FOUND_PLUGIN";
        $this->message = "$plugin plugin not found.";
    }
}