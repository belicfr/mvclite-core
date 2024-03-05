<?php

namespace MvcliteCore\Plugins\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class PluginAlreadyExistsException extends MvcLiteException
{
    public function __construct(string $plugin)
    {
        parent::__construct();

        $this->code = "MVCLITE_ALREADY_EXISTING_PLUGIN";
        $this->message = "$plugin plugin is already existing.";
    }
}