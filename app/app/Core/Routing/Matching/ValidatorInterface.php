<?php

namespace App\Core\Routing\Matching;

use App\Core\Request;
use App\Core\Routing\Route;

interface ValidatorInterface
{
    public function matches(Route $route, Request $request): bool;
}
