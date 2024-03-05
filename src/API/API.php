<?php

namespace MvcliteCore\API;

use MvcliteCore\API\Exceptions\NotFoundApiException;

class API
{
    public const NOT_FOUND = 404;

    public const SUCCESS = 200;

    private array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function getAllData(): array
    {
        return $this->data;
    }

    public function getData(string $key): mixed
    {
        return $this->getAllData()[$key] ?? null;
    }

    public function setData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function removeData(string $key): void
    {
        if (in_array($key, $this->getAllData()))
        {
            $this->data = array_slice($this->data, 0, -1);
        }
    }

    /**
     * Prepare the API response.
     *
     * @param int $status Request HTTP status
     * @return APIResponse
     */
    public function response(int $status): APIResponse
    {
        $response = new APIResponse($status, $this->getAllData());
        $response->send();

        return $response;
    }

    /**
     * Prepare a not found API response.
     *
     * @return APIResponse
     * @see API::response()
     * @see API::NOT_FOUND
     */
    public function respondNotFound(): APIResponse
    {
        return $this->response(self::NOT_FOUND);
    }

    /**
     * Prepare a success API response.
     *
     * @return APIResponse
     * @see API::response()
     * @see API::SUCCESS
     */
    public function respondSuccess(): APIResponse
    {
        return $this->response(self::SUCCESS);
    }

    public static function open(string $api): API
    {
        if (!class_exists($api))
        {
            $error = new NotFoundApiException($api);
            $error->render();
        }

        return new $api();
    }
}