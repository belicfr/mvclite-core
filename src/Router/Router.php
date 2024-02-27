<?php

namespace MvcliteCore\Router;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\InternalResources\Delivery;
use MvcliteCore\Router\Exceptions\UndefinedControllerMethodException;
use MvcliteCore\Router\Exceptions\UndefinedRouteException;

/**
 * Router main class.
 * Allows developer to create their own routes.
 *
 * @author belicfr
 */
class Router
{
    /** HTTP request method GET. */
    private const GET_METHOD = "GET";

    /** HTTP request method POST. */
    private const POST_METHOD = "POST";

    /** API route type. */
    private const API_TYPE = "API";

    /** Registered routes. */
    private static $routes = [];

    /**
     * Create a GET route.
     *
     * @param string $path URL path used by route
     * @param string $controller Controller used by route
     * @param string $method Controller method called by route
     * @return Route Created route object
     */
    public static function get(string $path, string $controller, string $method): Route
    {
        $parameters = Delivery::get()
            ->getRequest()
            ->getParameters();

        return self::$routes[] = new Route(self::GET_METHOD, $path, $controller, $method, $parameters);
    }

    /**
     * Create a POST route.
     *
     * @param string $path URL path used by route
     * @param string $controller Controller used by route
     * @param string $method Controller method called by route
     * @return Route Created route object
     */
    public static function post(string $path, string $controller, string $method): Route
    {
        $parameters = Delivery::get()
            ->getRequest()
            ->getParameters();

        return self::$routes[] = new Route(self::POST_METHOD, $path, $controller, $method, $parameters);
    }

    /**
     * Create an API route.
     *
     * @param string $path URL path used by route
     * @param string $controller Controller used by route
     * @param string $method Controller method called by route
     * @return Route Created route object
     */
    public static function api(string $path, string $controller, string $method): Route
    {
        $parameters = Delivery::get()
            ->getRequest()
            ->getParameters();

        return self::$routes[] = new Route(self::API_TYPE, $path, $controller, $method, $parameters);
    }

    /**
     * @return array Registered routes
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * @param string $path
     * @return Route Route object linked with given path
     */
    public static function getRouteByPath(string $path): Route
    {
        $route = array_filter(self::$routes, function (Route $route) use ($path)
        {
            return $route->getPath() == $path
                && $route->getHttpMethod() == self::getCurrentHttpMethod();
        });

        if (!count($route))
        {
            $error = new UndefinedRouteException();
            $error->render();
        }

        reset($route);

        $route = current($route);

        if ($route->getHttpMethod() !== self::getCurrentHttpMethod())
        {
            echo "WRONG HTTP METHOD!!";
            die;
        }

        return $route;
    }

    /**
     * @param Route $route Used route object
     */
    public static function useRoute(Route $route): void
    {
        $controllerInstance = new ($route->getController());
        $request = new Request();

        if (!method_exists($controllerInstance, $route->getMethod()))
        {
            $error = new UndefinedControllerMethodException($route->getController(), $route->getMethod());
            $error->render();
        }

        call_user_func([$controllerInstance, $route->getMethod()], $request);
    }

    /**
     * Attempt to return the route object from its name.
     *
     * @param string $routeName
     * @return Route|null Route object if exists or NULL
     */
    public static function getRouteByName(string $routeName): ?Route
    {
        $route = array_filter(self::$routes, function (Route $route) use ($routeName)
        {
            return $route->getName() == $routeName;
        });

        reset($route);

        return current($route) ?: null;
    }

    /**
     * @return string Current HTTP method
     */
    private static function getCurrentHttpMethod(): string
    {
        return count($_POST)
            ? self::POST_METHOD
            : self::GET_METHOD;
    }
}