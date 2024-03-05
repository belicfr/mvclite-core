<?php

namespace MvcliteCore\Plugins;

abstract class Plugin
{
    /** Plugin name. */
    protected string $name;

    public function __construct()
    {
        // Empty constructor.
    }

    /**
     * MVCLite event:
     * On application started.
     */
    public abstract function onStarted();

    /**
     * MVCLite event:
     * Before router is called.
     */
    public abstract function beforeRouter();

    /**
     * MVCLite event:
     * When router is retrieving the given route.
     */
    public abstract function onRouteRetrieving();

    /**
     * MVCLite event:
     * When the route has been found.
     */
    public abstract function onRouteFound();

    /**
     * MVCLite event:
     * If the route has not been found.
     */
    public abstract function onRouteNotFound();

    /**
     * @return string Plugin name
     */
    public function getName(): string
    {
        return $this->name;
    }
}