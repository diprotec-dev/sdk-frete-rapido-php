<?php

declare(strict_types=1);

namespace FreteRapido\Endpoints;

use FreteRapido\Endpoints\Abstracts\Endpoint;

class ShippingCost extends Endpoint
{

    public function calculate(array $args)
    {

        return $this->client->request($this->url(), 'POST', ['json' => $this->requestBody($args)]);
    }

    public function requestBody($args = [])
    {
     
        return [
            "remetente" => ["cnpj" => $this->client->auth['remetente_cnpj'] ?? ''],
            "codigo_plataforma" => $this->client->auth['platform_code'] ?? '',
            "token" => $this->client->auth['token'] ?? '',
            "expedidor" => [
                "cnpj" => $args['expedidor']['cnpj'] ?? '',
                "endereco" => [
                    "cep" => $args['expedidor']['endereco']['cep'] ?? ''
                ]
            ],
            "destinatario" => [
                "tipo_pessoa" => $args['destinatario']['tipo_pessoa'] ?? '',
                "cnpj_cpf" => $args['destinatario']['cnpj_cpf'] ?? '',
                "inscricao_estadual" => $args['destinatario']['inscricao_estadual'] ?? '',
                "endereco" => [
                    "cep" => $args['destinatario']['endereco']['cep'] ?? ''
                ]
            ],
            "volumes" =>  $args['volumes'] ?? [],
            "canal" => $args['canal'] ?? '',
            "cotacao_plataforma" => $args['cotacao_plataforma'] ?? '',
            "retornar_consolidacao" => $args['retornar_consolidacao'] ?? ''
        ];
    }

    public function url($args = [])
    {
        return self::BASE_URI_V1 . '/quote-simulator';
    }
}
