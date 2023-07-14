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

    $this->quote = new MockHandler([
        new Response(200, [], $this->jsonMock("Quote")),
    ]);

    $this->args = ["id_frete" => "FR230714OFWA1"];
});

test("should consult the information of a certain freight", function () {

    $this->freteRapido = new Client($this->auth, ['handler' => $this->quote]);

    $result = $this->freteRapido->quote()->execute($this->args)->get();

    $quote = json_decode($result);

    expect($result)->toBeJson();

    expect($quote->status)->toEqual(200);

    expect($quote->id_frete)->toBeString();
    expect($quote->numero_pedido)->toBeString();
    expect($quote->url_rastreio)->toBeString();
    expect($quote->data_prevista_entrega)->not->toBeEmpty();

});

test("should consult the information of a certain freight in array format", function () {

    $this->freteRapido = new Client($this->auth, ['handler' => $this->quote]);

    $result = $this->freteRapido->quote()->execute($this->args)->getArray();

    expect($result)->toBeArray();

    expect($result['status'])->toEqual(200);
});
