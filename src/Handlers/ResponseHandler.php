<?php

declare(strict_types=1);

namespace FreteRapido\Handlers;

class ResponseHandler
{
    private $body;
    private $code;

    public function __construct(array $body, int $code)
    {
        $this->body = $body;

        $this->code = $code;
    }

    public function get(): string
    {

        $this->body['status'] = $this->code;

        return json_encode($this->body);
    }

    public function getArray(): array
    {
        $this->body['status'] = $this->code;

        return $this->body;
    }

    public function getStatus(): int
    {
        return $this->code;
    }
}
