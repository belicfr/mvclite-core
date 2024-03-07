<?php

namespace MvcliteCore\Plugins;

class Plugin
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
    public function onStarted(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * Before project configuration file is loaded.
     */
    public function onBeforeConfigLoad(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * After project configuration file is loaded.
     */
    public function onAfterConfigLoad(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * Before Delivery is initialized.
     */
    public function onBeforeDeliveryFirstLoad(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * After Delivery is initialized.
     */
    public function onAfterDeliveryFirstLoad(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * Before router is called.
     */
    public function onBeforeRouter(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * After router is called.
     */
    public function onAfterRouter(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * Before delivery is reset.
     */
    public function onBeforeDeliveryReset(): void
    {
        // Empty event.
    }

    /**
     * MVCLite event:
     * After delivery is reset.
     */
    public function onAfterDeliveryReset(): void
    {
        // Empty event.
    }

    /**
     * @return string Plugin name
     */
    public function getName(): string
    {
        return $this->name;
    }
}