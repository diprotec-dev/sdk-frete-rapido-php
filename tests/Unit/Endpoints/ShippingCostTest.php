<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use FreteRapido\Client;


beforeEach(function () {

    $this->auth = [
        'remetente_cnpj' => '82193244000281',
        'token' => '123456',
        'platform_code' => 'ABC123'
    ];

    $this->ShippingCost = new MockHandler([
        new Response(200, [], $this->jsonMock("ShippingCost")),
    ]);


    $this->volumes = [[
        "tipo" => 999,
        "sku" => "ABC123",
        "tag" => "",
        "descricao" => "Produto descrição 01",
        "quantidade" => 1,
        "altura" => 0.250,
        "largura" => 0.080,
        "comprimento" => 0.150,
        "peso" => 0.500,
        "valor" => 9.90,
        "volumes_produto" => 1,
        "consolidar" => false,
        "sobreposto" => false,
        "tombar" => false
    ]];

    $this->args = [
        "expedidor" => [
            "cnpj" => "8219324400011",
            "endereco" => [
                "cep" => "80620010"
            ]
        ],
        "destinatario" => [
            "tipo_pessoa" => 1,
            "cnpj_cpf" => "12312312387",
            "inscricao_estadual" => "",
            "endereco" => [
                "cep" => "81710000"
            ]
        ],
        "canal" => "",
        "cotacao_plataforma" => 0,
        "retornar_consolidacao" => true,
        'volumes' => $this->volumes

    ];
});

test("should search for offers to shipping cost in json format", function () {

    $this->freteRapido = new Client($this->auth, ['handler' => $this->ShippingCost]);

    $result = $this->freteRapido->shippingCost()->calculate($this->args)->get();

    $shipping_cost = json_decode($result);

    $transportadora = $shipping_cost->transportadoras[0];

    expect(json_encode($shipping_cost))->toBeJson();

    expect($shipping_cost->status)->toEqual(200);

    expect($transportadora->oferta)->toBeInt();
    expect($transportadora->cnpj)->toBeNumeric();
    expect($transportadora->logotipo)->toBeString();
    expect($transportadora->nome)->toBeString();
    expect($transportadora->servico)->toBeString();
    expect($transportadora->prazo_entrega_minutos)->toBeInt();
    expect($transportadora->prazo_entrega_horas)->toBeInt();
    expect($transportadora->prazo_entrega)->not->toBeEmpty();
    expect($transportadora->validade)->not->toBeEmpty();
    expect($transportadora->preco_frete)->toBeFloat();
    expect($transportadora->custo_frete)->toBeFloat();

    expect(count($shipping_cost->transportadoras))->toBeGreaterThanOrEqual(1);
});

test("should search for offers to shipping cost in array format", function () {

    $this->freteRapido = new Client($this->auth, ['handler' => $this->ShippingCost]);

    $result = $this->freteRapido->shippingCost()->calculate($this->args)->getArray();

    expect($result)->toBeArray();

    expect($result['status'])->toEqual(200);
});
