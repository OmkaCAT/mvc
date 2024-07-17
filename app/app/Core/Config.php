<?php

namespace App\Core;

use App\Helpers\Arr;

class Config
{
    public function __construct(private array $items = [])
    {
        //
    }

    public function set($key, $value = null): void
    {
        Arr::set($this->items, $key, $value);
    }

    public function get($key, mixed $default = null): mixed
    {
        return Arr::get($this->items, $key, $default);
    }
}