<?php

namespace App\Models;

use App\Helpers\Arr;

abstract class Model
{
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function getAttribute(string $key): mixed
    {
        return Arr::get($this->attributes, $key);
    }

    public function setAttribute(string $key, mixed $value): void
    {
        if (Arr::has($this->properties(), $key)) {
            Arr::set($this->attributes, $key, $value);
        }
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    abstract function properties(): array;
}