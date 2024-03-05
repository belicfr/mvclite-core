<?php

namespace MvcliteCore\Plugins\Exceptions;

use MvcliteCore\Engine\MvcLiteException;

class NotFoundPluginException extends MvcLiteException
{
    public function __construct(string $pluginName, $pluginClass)
    {
        parent::__construct();

        $this->code = "MVCLITE_NOT_FOUND_PLUGIN";
        $this->message = "$pluginName plugin not found.<br />
                          <strong>Plugin class:</strong> $pluginClass";
    }
}