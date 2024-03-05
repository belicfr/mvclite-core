<?php

namespace MvcliteCore\Plugins;

abstract class Plugin
{
    /** Plugin name. */
    protected string $name;

    /**
     * MVCLite event:
     * On application started.
     */
    protected abstract function onStarted();

    /**
     * MVCLite event:
     * Before router is called.
     */
    protected abstract function beforeRouter();

    /**
     * MVCLite event:
     * When router is retrieving the given route.
     */
    protected abstract function onRouteRetrieving();

    /**
     * MVCLite event:
     * When the route has been found.
     */
    protected abstract function onRouteFound();

    /**
     * MVCLite event:
     * If the route has not been found.
     */
    protected abstract function onRouteNotFound();

    /**
     * @return string Plugin name
     */
    public function getName(): string
    {
        return $this->name;
    }
}