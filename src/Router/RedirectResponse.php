<?php

namespace MvcliteCore\Router;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\InternalResources\Delivery;
use MvcliteCore\Engine\Security\Validator;

/**
 * Redirection management class.
 *
 * @author belicfr
 */
class RedirectResponse
{
    /** Redirection route. */
    private Route $route;

    /** Current delivery object. */
    private Delivery $currentDelivery;

    private array $parameters;

    public function __construct(Route $route, array $parameters)
    {
        $this->route = $route;
        $this->currentDelivery = new Delivery();
        $this->parameters = $parameters;
    }

    /**
     * @return Route Redirection route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param Validator $validator Validator instance
     * @return $this Current RedirectResponse object
     */
    public function withValidator(Validator $validator): RedirectResponse
    {
        $this->currentDelivery
            ->setValidator($validator)
            ->save();

        return $this;
    }

    /**
     * @param Request $request Request instance
     * @return $this Current RedirectResponse object
     */
    public function withRequest(Request $request): RedirectResponse
    {
        $this->currentDelivery
            ->setRequest($request)
            ->save();

        return $this;
    }

    /**
     * Run redirection.
     */
    public function redirect(): void
    {
        header("Location: " . $this->route->getCompletePath() . $this->route->prepareParameters());
        die;
    }
}