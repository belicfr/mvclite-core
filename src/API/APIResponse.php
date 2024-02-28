<?php

namespace MvcliteCore\API;

use JsonSerializable;

class APIResponse implements JsonSerializable
{
    private int $status;

    private array $data;

    function __construct(int $status, array $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    function send(): void
    {
        echo json_encode($this);
        die;  // TODO: keep this die instruction?
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return [
            "status" => $this->getStatus(),
            "data" => $this->getData(),
        ];
    }
}