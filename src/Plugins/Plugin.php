<?php

namespace MvcliteCore\Plugins;

abstract class Plugin
{
    /** Plugin name. */
    protected string $name;

    public function __construct()
    {
        $this->name = "Plugin Name";
    }

    /**
     * MVCLite event:
     * On application started.
     */
    public abstract function onStarted(): void;

    /**
     * MVCLite event:
     * Before router is called.
     */
    public abstract function onBeforeRouter(): void;

    /**
     * MVCLite event:
     * After router is called.
     */
    public abstract function onAfterRouter(): void;

    /**
     * MVCLite event:
     * Before delivery is reset.
     */
    public abstract function onBeforeDeliveryReset(): void;

    /**
     * MVCLite event:
     * After delivery is reset.
     */
    public abstract function onAfterDeliveryReset(): void;

    /**
     * @return string Plugin name
     */
    public function getName(): string
    {
        return $this->name;
    }
}