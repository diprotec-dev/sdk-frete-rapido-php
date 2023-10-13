<?php

declare(strict_types=1);

namespace FreteRapido\Endpoints\Abstracts;

use FreteRapido\Client;

abstract class Endpoint
{

    const BASE_URI_V3 = "https://sp.freterapido.com/api/v3";

    const BASE_URI_V1 = "https://freterapido.com/api/external/embarcador/v1";

    const GET = "GET";

    const POST = "POST";

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
