<?php

namespace MvcliteCore\Router;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Router\Exceptions\UndefinedInputException;
use MvcliteCore\Router\Exceptions\UndefinedParameterException;

/**
 * Request manager class.
 *
 * @author belicfr
 */
class Request
{
    /** Current request URI. */
    private string $uri;

    /** Current request inputs. */
    private array $inputs;

    /** Current request parameters. */
    private array $parameters;

    public function __construct()
    {
        $this->uri = $_SERVER["REQUEST_URI"];

        $this->saveInputs();
        $this->saveParameters();
    }

    /**
     * Gets $_POST values and returns its neutralized version.
     *
     * @return array Inputs array
     */
    private function saveInputs(): array
    {
        $inputs = [];

        foreach ($_POST as $inputKey => $inputValue)
        {
            $inputs[$inputKey] = is_string($inputValue)
                ? htmlspecialchars($inputValue)
                : $inputValue;
        }

        return $this->inputs = $inputs;
    }

    /**
     * @return array Current request inputs
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }

    /**
     * @param string $key Input key
     * @param bool $neutralize If input value (string) must be neutralized
     * @return mixed Input value if exists;
     *               else NULL
     */
    public function getInput(string $key, bool $neutralize = true): mixed
    {
        if (!in_array($key, array_keys($this->getInputs())))
        {
            return null;
        }

        $input = $this->getInputs()[$key];

        return !$neutralize && is_string($input)
            ? htmlspecialchars_decode($input)
            : $input;
    }

    /**
     * @return string Decoded current request URI
     */
    public function getUri(): string
    {
        return urldecode($this->uri);
    }

    /**
     * Gets $_GET values and returns them.
     *
     * @return array Parameters array
     */
    private function saveParameters(): array
    {
        return $this->parameters = $_GET;
    }

    /**
     * @return array Current request parameters
     */
    public function getParameters(): array
    {
        return array_filter($this->parameters, function ($parameterKey)
        {
            return $parameterKey !== "route";
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param string $key Parameter key
     * @return string|null Parameter value if exists;
     *                     else NULL
     */
    public function getParameter(string $key): ?string
    {
        if (!in_array($key, array_keys($this->getParameters())))
        {
            return null;
        }

        return $this->getParameters()[$key];
    }
}