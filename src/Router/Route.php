<?php

namespace MvcliteCore\Router;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Router\Exceptions\AlreadyUsedRouteNameException;

/**
 * Class that represents a route and its own information.
 *
 * @author belicfr
 */
class Route
{
    /** HTTP request method: POST | GET */
    private string $httpMethod;

    /** Complete URL relative path linked to current route. */
    private string $completePath;

    /** URL path linked to current route. */
    private string $path;

    /** Controller used by current route. */
    private string $controller;

    /** Controller method called by current route. */
    private string $method;

    /** Route name. */
    private ?string $name;

    /** Redirection GET parameters. */
    private array $parameters;

    public function __construct(string $httpMethod,
                                string $path,
                                string $controller,
                                string $method,
                                array $parameters)
    {
        $pathPrefix = substr(ROUTE_PATH_PREFIX,
            0,
            strlen(ROUTE_PATH_PREFIX) - 1);

        $this->httpMethod = $httpMethod;
        $this->completePath = $pathPrefix . $path;
        $this->path = $path;
        $this->controller = $controller;
        $this->method = $method;
        $this->name = null;
        $this->parameters = $parameters;
    }

    /**
     * @return string Used HTTP method
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @return string Defined complete path
     */
    public function getCompletePath(): string
    {
        return $this->completePath;
    }

    /**
     * @return string Defined path
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string Used controller class
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string Called controller method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string|null Route name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name Route name
     */
    public function setName(string $name): void
    {
        if (Router::getRouteByName($name) !== null)
        {
            $error = new AlreadyUsedRouteNameException($name);
            $error->render();
        }

        $this->name = $name;
    }

    /**
     * @return array GET parameters
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters GET parameters array
     * @return array GET parameters
     */
    public function setParameters(array $parameters = []): array
    {
        return $this->parameters = $parameters;
    }

    /**
     * @return string Prepared parameters string (for URL)
     */
    public function prepareParameters(): string
    {
        $stringTuples = [];

        foreach ($this->getParameters() as $parameterKey => $parameter)
        {
            $stringTuples[] = "$parameterKey=$parameter";
        }

        return count($this->getParameters())
            ? "?" . implode('&', $stringTuples)
            : "";
    }
}