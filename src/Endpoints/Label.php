<?php

declare(strict_types=1);

namespace FreteRapido\Endpoints;

use FreteRapido\Endpoints\Abstracts\Endpoint;

class Label extends Endpoint
{
    public function execute(array $args)
    {
        return $this->client->request($this->url($args), 'POST', ['json' => $this->requestBody($args)]);
    }

    public function requestBody($args = []): array
    {
        return [["id_frete" => $args['id_frete'] ?? '']];
    }

    public function url($args = []): string
    {
        return self::BASE_URI_V1 . '/labels?token=' . $this->client->auth['token'] . '&layout=' . $args['layout'] . '&formato=' . $args['formato'];
    }
}
