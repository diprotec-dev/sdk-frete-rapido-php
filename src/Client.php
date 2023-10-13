<?php

declare(strict_types=1);

namespace FreteRapido;

use FreteRapido\Endpoints\Abstracts\Endpoint;
use FreteRapido\Endpoints\Collections\Endpoints;
use FreteRapido\Endpoints\ShippingCost;
use FreteRapido\Endpoints\ContractOffer;
use FreteRapido\Endpoints\Quote;
use FreteRapido\Endpoints\Label;
use FreteRapido\Endpoints\SendInvoice;
use FreteRapido\Exceptions\FreteRapidoException;
use FreteRapido\Exceptions\EndpointNotFound;
use FreteRapido\Handlers\UriHandler;
use FreteRapido\Handlers\ResponseHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * @method ShippingCost shippingCost()
 * @method ContractOffer contractOffer()
 * @method SendInvoice sendInvoice()
 * @method Quote quote()
 * @method Label label()
 */
class Client
{
    private GuzzleClient $client;

    private Endpoints $endpoints;

    public $auth;

    public function __construct(array $auth = [], array $config = [])
    {

        $this->client = new GuzzleClient($config);

        $this->auth = $auth;

        $this->loadDefaults();
    }

    public function request(string $uri, string $method = "GET", array $options = [])
    {
        try {
            $response = $this->client->request($method, UriHandler::format($uri), $options);

            $body = json_decode($response->getBody()->getContents(), true);

            if ($body == null) $body = [];

            if ($this->isValidResponse($body, $response->getStatusCode()))
                return new ResponseHandler($body, $response->getStatusCode());

            return (new FreteRapidoException("Freight not found", 404));
        } catch (RequestException $exception) {

            $message = json_decode($exception->getResponse()->getBody()->getContents(), true);

            if (!isset($message['error'])) $message['error'] = $exception->getMessage();

            return (new FreteRapidoException($message['error'], $exception->getCode()));
        } catch (GuzzleException $exception) {


            return (new FreteRapidoException($exception->getMessage(), $exception->getCode()));
        }
    }

    public function requestResult($body, $requestCode): string
    {
        $body['status'] = $requestCode;
        return json_encode($body);
    }

    public function addEndpoint(string $name, string $endpointClass): void
    {
        $this->endpoints->add($name, $endpointClass);
    }

    public function isValidResponse($body, $code): bool
    {
        return isset($body['transportadoras'][0]['oferta']) || isset($body['id_frete']) || isset($body[0]['etiqueta']) || ($body == null && $code == 200);
    }


    public function __call(string $name, array $arguments = []): Endpoint
    {
        if (!$this->endpoints->has($name)) {
            throw new EndpointNotFound("The endpoint {$name} doesn't exist.");
        }

        $endpoint = $this->endpoints->get($name);

        return new $endpoint($this);
    }

    private function loadDefaults(): void
    {
        $this->endpoints = new Endpoints([
            "shippingCost"   => ShippingCost::class,
            "contractOffer"  => ContractOffer::class,
            "sendInvoice"  => SendInvoice::class,
            "quote"  => Quote::class,
            "label"  => Label::class
        ]);
    }
}
