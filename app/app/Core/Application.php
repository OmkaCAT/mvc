<?php

namespace App\Core;

use App\Core\Routing\Router;

class Application
{
    private static ?Application $instance = null;

    private array $instances = [];

    private ?string $basePath = null;

    private string $configPath = '/config/';

    private function __construct()
    {
        //
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception();
    }

    public function setBasePath(string $basePath): self
    {
        $this->basePath = rtrim($basePath, '/');
        return $this;
    }

    public function setConfigPath(string $configPath): self
    {
        $this->configPath = $configPath;
        return $this;
    }

    public function boot(): void
    {
        if ($this->basePath === null) {
            throw new \Exception('Base path not set');
        }

        $this->loadConfiguration();
        $this->loadRoutes();
    }

    public function instance(string $abstract, mixed $instance): mixed
    {
        $this->instances[$abstract] = $instance;
        return $instance;
    }

    public function resolve(string $abstract): mixed
    {
        $instance = $this->instances[$abstract] ?? null;

        if ($instance === null) {
            throw new \Exception('Instance not found');
        }

        return $instance;
    }

    private function loadConfiguration(): void
    {
        $configDir = $this->basePath . $this->configPath;

        $files = $this->loadFiles($configDir);

        $config = new Config();
        foreach ($files as $key => $path) {
            $config->set($key, require $path);
        }

        $this->instance('config', $config);
    }

    private function loadRoutes(): void
    {
        /** @var Config $config */
        $config = $this->resolve('config');
        $routesDir = $this->basePath . '/' . $config->get('app.routes_dir') . '/';

        $files = $this->loadFiles($routesDir);

        $router = new Router();
        foreach ($files as $key => $path) {
            require $path;
        }

        $this->instance('router', $router);
    }

    private function loadFiles(string $dir): array
    {
        $files = [];

        foreach (glob($dir . '*.php') as $file) {
            $files[basename($file, '.php')] = $file;
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function run(Request $request): Response
    {
        try {
            /** @var Router $router */
            $router = $this->resolve('router');
            $response = $router->resolve($request);
        } catch (\Throwable $exception) {
            // todo render exception
            throw $exception;
        }

        return $response;
    }
}