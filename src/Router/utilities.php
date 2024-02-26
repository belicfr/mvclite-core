<?php

/*
 * Utilities router functions.
 */

use MvcliteCore\Engine\InternalResources\Delivery;
use MvcliteCore\Router\Request;
use MvcliteCore\Router\Router;

/**
 * Returns route path from its name.
 *
 * @param string $name Route name
 * @return string Route path
 */
function route(string $name, array $parameters = []): string
{
    $route = Router::getRouteByName($name);

    if ($route === null)
    {
        return '#';
    }

    $route->setParameters($parameters);

    return $route->getPath() . $route->prepareParameters();
}

/**
 * @return Delivery|null Current Delivery object
 */
function delivery(): ?Delivery
{
    return Delivery::get();
}

/**
 * @return Request
 */
function request(): Request
{
    return new Request();
}

/**
 * @param string $key GET parameter key
 * @return string|null GET parameter value
 */
function get(string $key): ?string
{
    return request()->getParameter($key);
}

/**
 * @param string $key POST input key
 * @return string|null POST input value
 */
function post(string $key): ?string
{
    return request()->getInput($key);
}

/**
 * @deprecated
 * @see Request::getUri()
 * @return string Current URI
 */
function uri(): string
{
    return request()->getUri();
}