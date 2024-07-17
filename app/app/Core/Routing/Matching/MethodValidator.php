<?php

namespace App\Core\Routing\Matching;

use App\Core\Request;
use App\Core\Routing\Route;

class MethodValidator implements ValidatorInterface
{
    public function matches(Route $route, Request $request): bool
    {
        return in_array($request->getMethod(), $route->getMethods());
    }
}
