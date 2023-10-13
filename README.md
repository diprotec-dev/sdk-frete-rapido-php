# FreteRapido PHP SDK


Abaixo o SDK possui exemplos de como consultar as cotações para frete além de realizar contratações.

## 1. Instalação

Via Composer

``` bash
$ composer require diprotec-dev/sdk-frete-rapido-php
```

## 2. Cotação de Frete - shippingCost


Como recuperar lista de ofertas de frete (PHP):

```php
<?php

use FreteRapido\Client;

$auth = [
    'remetente_cnpj' => '82193244000283',
    'token' => '1234567891012131415',
    'platform_code' => 'ABC123456'
];


$freteRapido = new Client($auth);

$args = [
    "expedidor" => [
        "cnpj" => "82193244000111",
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
    "volumes" => [
        [
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
        ]
    ],
    "canal" => "",
    "cotacao_plataforma" => 0,
    "retornar_consolidacao" => true
];

$shipping_cost = $freteRapido->shippingCost()->calculate($args)->get();

echo $shipping_cost;

```

Você deve receber um retorno via json semelhante ao resultado abaixo:

```json
{
  "token_oferta": "64af05d736e0cc3936f98a5e",
  "transportadoras": [
    {
      "oferta": 1,
      "cnpj": "69436534000161",
      "logotipo": "https://s3.amazonaws.com/public.prod.freterapido.uploads/transportadora/foto-perfil/69436534000161.png",
      "nome": "EXPRESSO FR (TESTE)",
      "servico": "Normal",
      "prazo_entrega_minutos": 31,
      "prazo_entrega_horas": 22,
      "prazo_entrega": 6,
      "entrega_estimada": "2023-07-20",
      "validade": "2023-08-11",
      "custo_frete": 80.96,
      "preco_frete": 80.96
    }
  ],
  "volumes": [
    {
      "tipo": 999,
      "sku": "ABC123",
      "tag": "",
      "descricao": "Produto descrição 01",
      "quantidade": 1,
      "altura": 0.25,
      "largura": 0.08,
      "comprimento": 0.15,
      "peso": 0.5,
      "valor": 9.9,
      "volumes_produto": 1
    }
  ],
  "status": 200
}

```

A resposta da request enviada retornará um json, se os dados de retornados conterem o item `{"status": 200}` , significa que a request foi realizada com com sucesso. (Para ter detalhes das possíveis mensagens de erro da api, acesse a documentação oficial no link: https://dev.freterapido.com/common/codigos_de_resposta/)

<b>Importante:</b> Para entender sobre todos os campos usados para fazer a request de cotação, acesse a documentação oficial no link https://dev.freterapido.com/ecommerce/cotacoes_de_frete/


## 3. Contratação de oferta de Frete - contractOffer


Para realizar uma contratação será necessário já ter feito uma simulação de contação e recuperar o `token_oferta` e o `id` da oferta. Ver exemplo abaixo:

```php
<?php

use FreteRapido\Client;

$auth = [
    'remetente_cnpj' => '82193244000281',
    'token' => '1234567891012131415',
    'platform_code' => 'ABC123456'
];

$freteRapido = new Client($auth);

$args = [
    "oferta" => [
        "id" => 1, //id da oferta
        "token" => "64b04f184817893724e94f01" //token_oferta
    ],
    "destinatario" => [
        "cnpj_cpf" => "12312312387",
        "nome" => "NOME PESSOA",
        "email" => "pessoanome@teste.com",
        "telefone" => "4198765432",
        "endereco" => [
            "cep" => "81710000",
            "rua" => "Rua Fracisco Derosso",
            "numero" => "100",
            "bairro" => "Xaxim",
            "complemento" => "Bloco X - Apto 1",
            "cidade" => "Curitiba",
            "estado" => "PR"
        ]
    ],
    "numero_pedido" => "DEF2024",
    "data_pedido" => "2023-07-08 16:12:13",
    "data_faturamento" => "2023-08-10 16:12:13",
    "obs_cliente" => "",
    "valor_frete_cobrado" => 110
];


$args['expedidor'] = [
    "cnpj" => "82193244000110",
    "razao_social" => "DIPROTEC DISTRIBUIDORA DE PROD",
    "inscricao_estadual" => "123456",
    "endereco" => [
        "cep" => "80620010",
        "rua" => "AVENIDA REPÚBLICA ARGENTINA",
        "numero" => "4486",
        "bairro" => "VILA IZABEL",
        "complemento" => "DIPROTEC"
    ]
];
 
// $args['nota_fiscal'] = [
//     "numero" => "",
//     "serie" => "",
//     "quantidade_volumes" => "",
//     "chave_acesso" => "",
//     "valor" => 0.00,
//     "valor_itens" => 0.00,
//     "data_emissao" => "",
//     "tipo_operacao" => 0,
//     "tipo_emissao" => 0,
//     "protocolo_autorizacao" => ""
// ];

// $args['metadados'] = [[
//     "chave" => "",
//     "valor" => "",
// ]];


$contract_offer = $freteRapido->contractOffer()->execute($args)->get();

echo $contract_offer;

```

<b>Importante:</b> Os campos comentados com '//' acima são opcionais.  Para entender sobre todos os campos usados para fazer a request de cotação, acesse a documentação oficial no link https://dev.freterapido.com/ecommerce/contratacao_de_frete/

Você deve receber um retorno via json semelhante ao resultado abaixo:

```json
{
  "id_frete": "FR12451CS2FF",
  "rastreio": "https://ondeestameupedido.com.br/FR230713CS2FF",
  "status": 200
}
```

A resposta da request enviada retornará um json, se os dados de retornados conterem o item `{"status": 200}` , significa que a request foi realizada com com sucesso. (Para ter detalhes das possíveis mensagens de erro da api, acesse a documentação oficial no link: https://dev.freterapido.com/common/codigos_de_resposta/)

## 4. Consulta de Frete pelo ID Frete Rápido
Método que permite consultar as informações de um determinado frete. Serão retornados os dados disponíveis sobre o frete.

```php
<?php

use FreteRapido\Client;

$auth = [
    'remetente_cnpj' => '82193244000281',
    'token' => '1234567891012131415',
    'platform_code' => 'ABC123456'
];

$freteRapido = new Client($auth);

$args = ["id_frete" => "FR230714O123"];

$quote = $freteRapido->quote()->execute($args)->get();

echo $quote;
```
Você deve receber um retorno via json semelhante ao resultado abaixo:

```json
{
  "id_frete": "FR230714OFWAO",
  "url_rastreio": "https://ondeestameupedido.com.br/FR230714OFWAO",
  "numero_pedido_pai": null,
  "numero_pedido": "1346200500862-01",
  "data_pedido": null,
  "data_faturamento": null,
  "data_prevista_entrega": "2023-07-25",
  "forma_pagamento": null,
  "obs_cliente": "Ligar para cliente antes da entrega",
  "data_contratacao": "2023-07-14 11:28:07",
  "tipo_cobranca": "CIF",
  "modal": "Rodoviário",
  "data_coleta": "",
  "embarcador": {
    "cnpj": "82.193.244/0001-00",
    "inscricao_estadual": "10177667-04",
    "razao_social": "DIPROTEC DISTRIBUIDORA DE PRODUTOS TECNICOS PARA CONSTRUCAO CIVIL LTDA",
    "nome_fantasia": "CONTA DE TESTE",
    "endereco": {
      "rua": "AVENIDA REPÚBLICA ARGENTINA",
      "numero": "1155",
      "complemento": "",
      "bairro": "VILA IZABEL",
      "cidade": "Curitiba",
      "estado": "PR",
      "cep": "80620-010"
    }
  },
  "destinatario": {
    "cnpj_cpf": "734.892.472-72",
    "nome": "LEONIDAS AMORIM",
    "endereco": {
      "rua": "RUA DONA ALICE TIBIRIÇÁ,",
      "numero": "244",
      "complemento": "BLOCO 1B - APTO 12",
      "bairro": "BIGORRILHO",
      "cidade": "Curitiba",
      "estado": "PR",
      "cep": "80730-320"
    }
  },
  "expedidor": {
    "cnpj": "82.193.244/0001-00",
    "inscricao_estadual": "123456",
    "razao_social": "DIPROTEC DISTRIBUIDORA DE PROD",
    "nome_fantasia": "",
    "endereco": {
      "rua": "AVENIDA REPÚBLICA ARGENTINA",
      "numero": "1155",
      "complemento": "",
      "bairro": "VILA IZABEL",
      "cidade": "Curitiba",
      "estado": "PR",
      "cep": "80620-010"
    }
  },
  "volumes": [
    {
      "tipo": "Outros",
      "sku": "16317",
      "descricao": "Aditivo Plastificante Sika Concreto Forte Sache 1L SIKA",
      "quantidade": 1,
      "peso_total": 1.07,
      "valor_total": 7.39,
      "altura": 0.25,
      "largura": 0.09,
      "comprimento": 0.09,
      "volumes_produto": 9,
      "itens": [ ]
    }
  ],
  "transportadora": {
    "cnpj": "69.436.534/0001-61",
    "razao_social": "TRANSPORTADORA EXPRESSO FR (TESTE)",
    "nome_fantasia": "EXPRESSO FR (TESTE)",
    "inscricao_estadual": "ISENTO",
    "valor_cotado": 81,
    "servico": "Normal",
    "endereco": {
      "cep": "29730000",
      "bairro": "CENTRO",
      "rua": "RUA CARLOS LUIZ FREDERICO",
      "numero": "05",
      "complemento": "",
      "cidade": "Baixo Guandu",
      "estado": "ES"
    }
  },
  "codigos": {
    "correios": [ ],
    "transportadora": [ ],
    "redespacho": [ ]
  },
  "ultimo_status": {
    "codigo": 1,
    "nome": "Aguardando coleta / postagem",
    "data_ocorrencia": "2023-07-14 11:28:07",
    "data_atualizacao": "2023-07-14 11:28:07",
    "data_reentrega": "",
    "prazo_devolucao": "",
    "mensagem": ""
  },
  "nfe": [ ],
  "cte": [ ],
  "ocorrencias": [
    {
      "codigo": 1,
      "nome": "Aguardando coleta / postagem",
      "data_ocorrencia": "2023-07-14 11:28:07",
      "data_atualizacao": "2023-07-14 11:28:07",
      "data_reentrega": null,
      "prazo_devolucao": null,
      "mensagem": null,
      "razao_social_transportadora": null,
      "descricao_ocorrencia": null,
      "codigo_ocorrencia": null,
      "nome_entregador": null,
      "cnpj_cpf_entregador": null,
      "comprovantes": [ ],
      "codigos_redespacho": [ ]
    },
    {
      "codigo": 0,
      "nome": "Solicitado",
      "data_ocorrencia": "2023-07-14 11:28:07",
      "data_atualizacao": "2023-07-14 11:28:07",
      "data_reentrega": null,
      "prazo_devolucao": null,
      "mensagem": null,
      "razao_social_transportadora": null,
      "descricao_ocorrencia": null,
      "codigo_ocorrencia": null,
      "nome_entregador": null,
      "cnpj_cpf_entregador": null,
      "comprovantes": [ ],
      "codigos_redespacho": [ ]
    }
  ],
  "metadados": [ ],
  "status": 200
}
```

## 5. Etiqueta
Método que permite gerar as etiquetas de um determinado frete, passando o id_frete, layout e formato. segue o exemplo:

```php
<?php

use FreteRapido\Client;

$auth = [
    'remetente_cnpj' => '82193244000281',
    'token' => '1234567891012131415',
    'platform_code' => 'ABC123456'
];

$freteRapido = new Client($auth);

$args = [
    "id_frete" => "FR230714O123",
    "layout" => "3",
    "formato" => "pdf"
];

$label = $freteRapido->label()->execute($args)->get();

echo $label;
```
Você deve receber um retorno via json semelhante ao resultado abaixo, com o item `etiqueta` incluindo a url para download em pdf :

```json
[
    {
        "id_frete": [
            "FR2307173266J"
        ],
        "chave_nf": [
            "35230722907528000181550010000599951937121014"
        ],
        "numero_pedido": [
            "DEF2024"
        ],
        "origem": {
            "cnpj": "82193244000100",
            "endereco": {
                "cep": "80620010",
                "rua": "AVENIDA REPÚBLICA ARGENTINA",
                "numero": "1155",
                "bairro": "VILA IZABEL",
                "cidade": "Curitiba",
                "uf": "PR"
            }
        },
        "etiqueta": "https://s3.amazonaws.com/prod.freterapido.uploads/tmp/freterapido_a143dee3f9824e89b709a414d2269247.pdf?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJJKNUFFWI33F6GPA%2F20230718%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20230718T142403Z&X-Amz-Expires=86400&X-Amz-SignedHeaders=host&response-content-disposition=attachment&X-Amz-Signature=998a41668409bd1d1b5a2bb35fccc0f2a1687041fa16ed984811d334cd2e01c2"
    }
]
```

Para ter mais detalhes dos campos utilizados na request de geração de etiquetas, acesse a documentação oficial: https://dev.freterapido.com/ecommerce/etiquetas/

## 6. Envio de Nota Fiscal - 


Para realizar uma contratação será necessário já ter feito uma contração e recuperar o `id_frete`.  Ver exemplo abaixo:

```php
<?php

use FreteRapido\Client;

$auth = [
    'remetente_cnpj' => '82193244000281',
    'token' => '1234567891012131415',
    'platform_code' => 'ABC123456'
];

$freteRapido = new Client($auth);

$args = [
    "id_frete" => "FR231013RQW01", //id_frete recuperado na contratação
    "nota_fiscal" => [
    [
        "numero" => "28209",
        "serie" => "5",
        "cfop" => "",
        "chave_acesso" => "41230923200952100154550020000247161950519450",
        "quantidade_volumes" => "1",
        "valor" => 81.00,
        "valor_itens" => 81,00,
        "data_emissao" => "2019-10-06 13:40:00",
        "tipo_operacao" => 1,
        "tipo_emissao" => 1,
        "protocolo_autorizacao" => "333230109061"
    ]
]];

$send_invoice = $freteRapido->sendInvoice()->execute($args)->get();

echo $send_invoice;

```

<b>Importante:</b> No nó do array 'nota_fiscal' => [] você pode passar mais de uma nota se precisar.  Para entender sobre todos os campos usados para fazer a request de cotação, acesse a documentação oficial no link https://dev.freterapido.com/ecommerce/envio_da_nfe/

Você deve receber um retorno via json semelhante ao resultado abaixo:

```json
{
  "status": 200
}
```

A resposta da request enviada retornará um json, se os dados de retornados conterem o item `{"status": 200}` , significa que a request foi realizada com com sucesso. (Para ter detalhes das possíveis mensagens de erro da api, acesse a documentação oficial no link: https://dev.freterapido.com/common/codigos_de_resposta/)


## 7. Teste

O SDK possui testes unitários que encontram-se na pasta `/tests`. Para executar todos os testes execute o comando na raiz da pasta do sdk.

``` bash
$ composer test
```

## 8. Segurança

Se você descobrir algum problema relacionado à segurança, envie um e-mail para ti@diprotec.com.br


## 9. Licença

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.