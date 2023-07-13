<?php

declare(strict_types=1);

namespace FreteRapido\Endpoints;

use FreteRapido\Endpoints\Abstracts\Endpoint;

error_reporting(E_ALL);
ini_set('display_errors', '1');

class ContractOffer extends Endpoint
{
    public function execute(array $args)
    {
        return $this->client->request($this->url($args), 'POST', ['json' => $this->requestBody($args)]);
    }

    public function requestBody($args = [])
    {
        $requestBody = [
            "remetente" => ["cnpj" => $this->client->auth['remetente_cnpj'] ?? ''],
            "expedidor" => $this->normalize($args['expedidor']),
            "destinatario" => $this->normalize($args['destinatario']),
            "numero_pedido" => $args['numero_pedido'],
            "data_pedido" => $args['data_pedido'],
            "data_faturamento" => $args['data_faturamento'],
            "forma_pagamento" => $args['forma_pagamento'] ?? '',
            "obs_cliente" => $args['obs_cliente'],
            "valor_frete_cobrado" => 0.00,
            "canal" => $args['canal'] ?? '',
            "subcanal" => $args['subcanal'] ?? ''
        ];

        if (isset($args['metadados'])) $requestBody['metadados'] = $this->normalize($args['metadados']);
        if (isset($args['nota_fiscal'])) $requestBody['nota_fiscal'] = $this->normalize($args['nota_fiscal']);

        return $requestBody;
    }

    public function normalize($arg_array)
    {
        return array_map(fn ($value) => is_array($value) ? array_map(fn ($nestedValue) => $nestedValue, $value) : $value, $arg_array);
    }

    public function url($args = [])
    {
        return self::BASE_URI_V1 . '/quote/ecommerce/' . $args['oferta']['token'] .
            '/offer/' . $args['oferta']['id'] . '?token=' . $this->client->auth['token'];
    }
}
