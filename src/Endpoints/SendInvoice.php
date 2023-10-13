<?php

declare(strict_types=1);

namespace FreteRapido\Endpoints;

use FreteRapido\Endpoints\Abstracts\Endpoint;

class SendInvoice extends Endpoint
{
    public function execute(array $args)
    {

        return $this->client->request($this->url($args), 'POST', ['json' => $this->requestBody($args)]);
    }

    public function requestBody($args = [])
    {
        $requestBody = [];
        $requestBody['nota_fiscal'] = $this->normalize($args['nota_fiscal']);

        return $requestBody;
    }

    public function normalize($arg_array)
    {
        return array_map(fn ($value) => is_array($value) ? array_map(fn ($nestedValue) => $nestedValue, $value) : $value, $arg_array);
    }

    public function url($args = [])
    {
        return self::BASE_URI_V1 . '/quotes/' . $args['id_frete'] .'/invoices?token=' . $this->client->auth['token'];
    }
}
