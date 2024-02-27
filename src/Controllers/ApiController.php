<?php

namespace MvcliteCore\Controllers;

class ApiController
{
    public function end(int $status, array $data): void
    {
        $response = [
            "status" => $status,
            "data"   => $data,
        ];

        echo json_encode($response);
    }
}