<?php

namespace App\Core\Routing\Matching;

use App\Core\Request;
use App\Core\Routing\Route;

class UriValidator implements ValidatorInterface
{
    public function matches(Route $route, Request $request): bool
    {
        $path = rtrim($request->getPath(), '/') ?: '/';

        return $route->getUri() === $path;
    }
}
