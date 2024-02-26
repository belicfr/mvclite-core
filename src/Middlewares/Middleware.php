<?php

namespace MvcliteCore\Middlewares;

class Middleware
{
    public function __construct()
    {
        // Empty constructor.
    }

    public function run(): mixed
    {
        return true;
    }
}