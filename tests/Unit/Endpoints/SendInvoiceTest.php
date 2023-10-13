<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use FreteRapido\Client;


beforeEach(function () {

    $this->auth = ['token' => '123456'];

    $this->SendInvoice = new MockHandler([
        new Response(200, [], $this->jsonMock("SendInvoice")),
    ]);


    $this->args = ["id_frete" => "FR231005RUWOD",
    "nota_fiscal" => [
    [
        "numero" => "28209",
        "serie" => "5",
        "cfop" => "",
        "chave_acesso" => "41230923200952000154550020000247161950519450",
        "quantidade_volumes" => "1",
        "valor" => 81.00,
        "valor_itens" => 81,00,
        "data_emissao" => "2019-10-06 13:40:00",
        "tipo_operacao" => 1,
        "tipo_emissao" => 1,
        "protocolo_autorizacao" => "333230109064"
    ]]];
});

test("should return a contract of offer freight in json format", function () {

    $this->freteRapido = new Client($this->auth, ['handler' => $this->SendInvoice]);

    $result = $this->freteRapido->sendInvoice()->execute($this->args)->get();
  
    $send_invoice = json_decode($result);

    expect($result)->toBeJson();
    expect($send_invoice->status)->toEqual(200);

});