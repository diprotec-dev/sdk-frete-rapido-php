<?php

declare(strict_types=1);

namespace FreteRapido\Endpoints;

use FreteRapido\Endpoints\Abstracts\Endpoint;

class Quote extends Endpoint
{
    public function execute(array $args)
    {
        return $this->client->request($this->url($args), 'GET');
    }

    public function url($args = [])
    {
        return self::BASE_URI_V1 . '/quote/' . $args['id_frete'] . '?token=' . $this->client->auth['token'];
    }
}
