<?php

namespace App\Core\Routing;

use App\Core\Exceptions\HttpException;
use App\Core\Request;
use App\Core\Response;

class Router
{
    protected array $routes = [];

    public function addRoute(array $methods, string $path, array $action): void
    {
        $route = $this->createRoute($methods, $path, $action);
        $this->routes[] = $route;
    }

    private function createRoute(array $methods, string $uri, array $action): Route
    {
        return new Route($methods, $uri, $action);
    }

    public function resolve(Request $request): Response
    {
        $route = $this->findRoute($request);

        if (!$route) {
            throw new HttpException(404);
        }

        return $this->toResponse($request, $route->run($request));
    }

    private function toResponse(Request $request, mixed $response)
    {
        // todo другой вид контента
        if (is_string($response)) {
            $response = new Response($response, 200, ['Content-Type' => 'text/html']);
        }

        return $response;
    }

    protected function findRoute(Request $request): ?Route
    {
        $foundRoute = null;

        /** @var Route $route */
        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                $foundRoute = $route;
                break;
            }
        }

        return $foundRoute;
    }
}