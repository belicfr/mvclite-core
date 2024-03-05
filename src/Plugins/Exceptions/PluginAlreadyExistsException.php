<?php

namespace MvcliteCore\Plugins\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class PluginAlreadyExistsException extends MvcLiteException
{
    public function __construct(string $pluginName, string $currentPluginClassName = "Unknown")
    {
        parent::__construct();

        $this->code = "MVCLITE_ALREADY_EXISTING_PLUGIN";
        $this->message = "$pluginName plugin is already existing.<br />
                          <strong>Current plugin class:</strong> $currentPluginClassName";
    }
}