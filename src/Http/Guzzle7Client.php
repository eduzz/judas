<?php

namespace Eduzz\Judas\Http;

use GuzzleHttp\Client;

class Guzzle7Client extends AbstractClient implements HttpClientInterface
{

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function post($url, $headers, $body)
    {
        if (!$headers || !is_array($headers)) {
            $headers = [];
        }

        $promise = $this->client->postAsync($url, [
            "headers" => $headers,
            "json" => json_decode($body)
        ]);

        $result = $promise->wait();

        return $result;
    }
}