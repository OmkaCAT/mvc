<?php

namespace App\Core;

class Request
{
    private const array SUPPORTED_METHODS = [
        'GET',
        'HEAD',
        'POST',
        'PUT',
        'DELETE',
        'CONNECT',
        'OPTIONS',
        'PATCH',
        'PURGE',
        'TRACE'
    ];

    public ParameterBag $query;
    public ParameterBag $request;
    public ParameterBag $cookies;
    public ParameterBag $files;
    public ParameterBag $server;

    protected ?string $method = null;

    protected ?string $path = null;

    protected ?string $uri = null;

    public function __construct(
        array $query = [],
        array $request = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
    ) {
        $this->query = new ParameterBag($query);
        $this->request = new ParameterBag($request);
        $this->cookies = new ParameterBag($cookies);
        $this->files = new ParameterBag($files);
        $this->server = new ParameterBag($server);
    }

    public static function createFromGlobals(): static
    {
        return new static(
            query: $_GET,
            request: $_POST,
            cookies: $_COOKIE,
            files: $_FILES,
            server: $_SERVER
        );
    }

    public function getMethod(): string
    {
        if (null !== $this->method) {
            return $this->method;
        }

        $method = strtoupper($this->server->get('REQUEST_METHOD', 'GET'));

        if (!in_array($method, self::SUPPORTED_METHODS, true)) {
            throw new \UnexpectedValueException();
        }

        return $this->method = $method;
    }

    public function getPath(): string
    {
        if (null !== $this->path) {
            return $this->path;
        }

        $uri = $this->getUri();

        if (null === $uri) {
            return $this->path = '/';
        }

        // Remove the query string from REQUEST_URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return $this->path = $uri;
    }

    public function getUri(): ?string
    {
        if (null !== $this->uri) {
            return $this->uri;
        }

        return $this->uri = $this->server->get('REQUEST_URI');
    }

    public function all(): array
    {
        $method = $this->getMethod();

        return in_array($method, ['GET', 'HEAD']) ? $this->query->all() : $this->request->all();
    }
}