<?php

namespace Eduzz\Judas\Http;

use GuzzleHttp\Client;

class Guzzle6Client implements HttpClientInterface
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

        $result = $this->client->postAsync($url, [
            "headers" => $headers,
            "body" => $body
        ]);

        return $result->wait();
    }
}