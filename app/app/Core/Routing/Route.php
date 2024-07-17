<?php

namespace App\Core\Routing;

use App\Core\Request;
use App\Core\Routing\Matching\MethodValidator;
use App\Core\Routing\Matching\UriValidator;

class Route
{
    public function __construct(private array $methods, private string $uri, private array $action)
    {
        //
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getAction(): array
    {
        return $this->action;
    }

    public function matches(Request $request): bool
    {
        $validators = [
            new UriValidator,
            new MethodValidator,
        ];

        foreach ($validators as $validator) {
            if (! $validator->matches($this, $request)) {
                return false;
            }
        }

        return true;
    }

    public function run(Request $request): mixed
    {
        try {
            $controllerClass = $this->action[0] ?? '';

            if (!class_exists($controllerClass)) {
                throw new \LogicException("Unable to load class: $controllerClass");
            }

            $controller = new $controllerClass;

            $method = $this->action[1] ?? 'index';

            if (!method_exists($controller, $method)) {
                throw new \LogicException("Unable to run action: $method");
            }
        } catch (\Throwable $exception) {
            throw new \LogicException('Failed to resolve action');
        }

        return $controller->{$method}($request);
    }
}