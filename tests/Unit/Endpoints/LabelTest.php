<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use FreteRapido\Client;


beforeEach(function () {

    $this->auth = ['token' => '123456'];

    $this->Label = new MockHandler([
        new Response(200, [], $this->jsonMock("Label")),
    ]);

    $this->args = [
        "id_frete" => "FR2307173266J",
        "layout" => "3",
        "formato" => "pdf"
    ];
});

test("should return a label of Freight in json format", function () {

    $this->freteRapido = new Client($this->auth, ['handler' => $this->Label]);

    $result = $this->freteRapido->label()->execute($this->args)->get();

    $label = json_decode($result, true);

    expect($result)->toBeJson();

    expect($label['status'])->toEqual(200);

    expect($label[0]['id_frete'][0])->toBeString();

    expect($label[0]['chave_nf'][0])->toBeString();

    expect($label[0]['numero_pedido'][0])->toBeString();

    expect($label[0]['etiqueta'])->toBeString();

});

test("sshould return a label of Freight in array format", function () {

    $this->freteRapido = new Client($this->auth, ['handler' => $this->Label]);

    $result = $this->freteRapido->label()->execute($this->args)->getArray();

    expect($result)->toBeArray();

    expect($result['status'])->toEqual(200);

    expect($result[0]['id_frete'][0])->toBeString();

    expect($result[0]['chave_nf'][0])->toBeString();

    expect($result[0]['numero_pedido'][0])->toBeString();

    expect($result[0]['etiqueta'])->toBeString();

});
