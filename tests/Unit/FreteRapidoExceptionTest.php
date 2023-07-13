<?php

declare(strict_types=1);

namespace Unit;

use FreteRapido\Exceptions\FreteRapidoException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use FreteRapido\Client;

beforeEach(function () {
    

    $this->auth = [
        'remetente_cnpj' => '82193244000283',
        'token' => '123456',
        'platform_code' => 'ABC123'
    ];

    $this->args = [
        "expedidor" => [
            "cnpj" => "82193244000100",
            "endereco" => [
                "cep" => "80620010"
            ]
        ],
        "destinatario" => [
            "tipo_pessoa" => 1,
            "cnpj_cpf" => "12312387",
            "inscricao_estadual" => "",
            "endereco" => [
                "cep" => "81710000"
            ]
        ],
        "volumes" => [
            [
                "tipo" => 999,
                "sku" => "DEF2024",
                "tag" => "",
                "descricao" => "Produto descrição 04",
                "quantidade" => 2,
                "altura" => 0.260,
                "largura" => 0.090,
                "comprimento" => 0.250,
                "peso" => 0.600,
                "valor" => 8.50,
                "volumes_produto" => 2,
                "consolidar" => false,
                "sobreposto" => false,
                "tombar" => false
            ]
        ],
        "canal" => "",
        "cotacao_plataforma" => 0,
        "retornar_consolidacao" => true
    ];

});

test("return error when shipper is incorrect", function () {

    $auth = [
        'remetente_cnpj' => '1234567891523', /* invalid shipper */
        'token' => '123456',
        'platform_code' => 'ABC123'
    ];

    $this->freteRapido = new Client($auth);

    $result = $this->freteRapido->shippingCost()->calculate($this->args)->get();

    $shipping_cost = json_decode($result);

    expect(json_encode($shipping_cost))->toBeJson();

    expect($shipping_cost->status)->toEqual(401);

});



test("return error when Freight not found", function () {

    $mock = new MockHandler([
        new Response(400, [], ""),
    ]);

    $this->freteRapido = new Client([], ['handler' => $mock]);

    $result = $this->freteRapido->shippingCost()->calculate($this->args)->get();

    $shipping_cost = json_decode($result);

    expect($result)->toBeJson();
    
    expect($shipping_cost->status)->toEqual(404);

    expect($shipping_cost->error)->toEqual('Freight not found');

});