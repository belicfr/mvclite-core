<?php

namespace MvcliteCore\Engine\InternalResources;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\Security\Validator;
use MvcliteCore\Router\Request;

/**
 * Delivery management class.
 *
 * @author belicfr
 */
class Delivery
{
    /** Key used to store delivery data in $_POST. */
    public const DELIVER_POST_KEY = "internal_delivery";

    /** Current validator object. */
    private ?Validator $validator;

    /** Current request object. */
    private ?Request $request;

    /** Current global properties array. */
    private array $props;

    /**
     * Route usage iteration count.
     * Used to destroy Delivery current instance
     * after 2 usages.
     */
    private int $routingIterationCount;

    /**
     * @return Delivery Current delivery object if exists;
     *                  else NULL
     */
    public static function get(): Delivery
    {
        return unserialize($_SESSION[self::DELIVER_POST_KEY]);
    }

    public function __construct(?Validator $currentValidator = null)
    {
        $this->request = new Request();
        $this->validator = $currentValidator ?? new Validator($this->getRequest());
        $this->props = [];
        $this->routingIterationCount = 0;
    }

    /**
     * @return bool If there is a validator stored in
     */
    public function hasValidator(): bool
    {
        return $this->validator !== null;
    }

    /**
     * @return Validator|null Validator instance if exists;
     *                        else NULL
     */
    public function getValidator(): ?Validator
    {
        return $this->validator;
    }

    /**
     * @param Validator $validator Validator instance
     */
    public function setValidator(Validator $validator): Delivery
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Add global property to current delivery instance.
     *
     * @param string $key
     * @param mixed $value
     * @return Delivery Current delivery object
     */
    public function add(string $key, mixed $value): Delivery
    {
        $this->props[$key] = $value;

        return $this;
    }

    /**
     * @return array Global properties array
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * @return Request|null Current request object if exists;
     *                      else NULL
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * @param Request $request Request instance
     */
    public function setRequest(Request $request): Delivery
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return bool If there is a request object
     */
    public function hasRequest(): bool
    {
        return $this->getRequest() !== null;
    }

    public function getRoutingIterationCount(): int
    {
        return $this->routingIterationCount;
    }

    public function mustBeDestroyed(): bool
    {
        return $this->routingIterationCount > 1;
    }

    public function incrementRoutingIterationCount(): Delivery
    {
        $this->routingIterationCount++;

        return $this;
    }

    /**
     * Store in session variable the current delivery instance serialized.
     *
     * @return Delivery Current delivery object
     */
    public function save(): Delivery
    {
        $_SESSION[self::DELIVER_POST_KEY] = serialize($this);

        return $this;
    }
}