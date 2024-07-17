<?php

namespace App\Core\Routing\Matching;

use App\Core\Request;
use App\Core\Routing\Route;

class UriValidator implements ValidatorInterface
{
    public function matches(Route $route, Request $request): bool
    {
        $path = rtrim($request->getPath(), '/') ?: '/';

        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)}/', '(?P<\1>[a-zA-Z0-9_]+)', $route->getUri());

        return preg_match('#^' . $pattern . '$#', $path);
    }
}
