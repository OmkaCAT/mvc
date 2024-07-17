<?php

namespace App\Core;

class Response
{
    private ParameterBag $headers;

    private ?string $content;

    private int $statusCode;

    public function __construct(?string $content = '', int $status = 200, array $headers = [])
    {
        $this->headers = new ParameterBag($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode(int $status): static
    {
        $this->statusCode = $status;
        return $this;
    }

    public function send(): static
    {
        $this->sendHeaders();
        $this->sendContent();

        $flush = 1 <= \func_num_args() ? func_get_arg(0) : true;
        if (!$flush) {
            return $this;
        }

        fastcgi_finish_request();

        return $this;
    }

    public function sendHeaders(): static
    {
        if (headers_sent()) {
            return $this;
        }

        $statusCode = \func_num_args() > 0 ? func_get_arg(0) : null;

        $headers = [];
        foreach ($this->headers->all() as $name => $value) {
            $headers[$name] = $value;
        }

        // todo cookies
        if (isset($headers['set-cookie'])) {
            unset($headers['set-cookie']);
        }

        foreach ($headers as $name => $values) {
            foreach ($values as $value) {
                header($name . ': '.$value, false, $this->statusCode);
            }
        }

        // status
        header(sprintf('HTTP/%s %s %s', '1.0', $statusCode, ''), true, $statusCode);

        // todo headers
        return $this;
    }

    public function sendContent(): static
    {
        echo $this->content;

        return $this;
    }
}