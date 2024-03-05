<?php

namespace MvcliteCore\Plugins;

class Plugin
{
    /** Plugin name. */
    protected string $name;

    public function __construct()
    {
        $this->name = null;  // Default value  =>  NULL
    }

    /**
     * @return string Plugin name
     */
    public function getName(): string
    {
        return $this->name;
    }
}