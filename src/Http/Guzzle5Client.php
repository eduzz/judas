<?php

namespace Eduzz\Judas\Http;

use GuzzleHttp\Client;

class Guzzle5Client extends AbstractClient implements HttpClientInterface
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

        $result = $this->client->post($url, [
            "future" => true,
            "headers" => $headers,
            "body" => $body
        ]);

        $this->await($result);

        return $result;
    }
}