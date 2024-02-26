<?php

namespace MvcliteCore\Controllers;

use MvcliteCore\Middlewares\Middleware;

/**
 * As part of the framework, the controller serves as the
 * intermediary between the view and the model.
 *
 * It is responsible for the logical aspects of a page and for
 * facilitating the communication of dynamic data, referred to as properties or props, to the views.
 *
 * @author belicfr
 */
class Controller
{
    public function __construct()
    {
        // Empty constructor.
    }

    /**
     * Run a middleware.
     *
     * @param string $middleware
     */
    protected function middleware(string $middleware): void
    {
        $middlewareInstance = new $middleware();
        $middlewareInstance->run();
    }
}