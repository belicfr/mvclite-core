<?php

namespace MvcliteCore\Controllers;

use MvcliteCore\API\API;
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
    private array $middlewares;

    public function __construct()
    {
        $this->middlewares = [];
    }

    /**
     * Register and run a middleware.
     *
     * @param string $middleware
     */
    protected function middleware(string $middleware): void
    {
        $middlewareInstance = new $middleware();
        $this->addMiddleware($middlewareInstance);
        $middlewareInstance->run();
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    private function addMiddleware(Middleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }
}