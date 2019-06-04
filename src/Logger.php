<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Http\HttpClientFactory;
use GuzzleHttp\Client;

class Logger
{

    /* @var \GuzzleHttp\Client */
    private $client;
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token, $client = null)
    {
        $this->client = $client;

        if (!$client) {
            $factory = new HttpClientFactory();
            $this->client = $factory->createHttpClient();
        }

        if (!$baseUrl) {
            $baseUrl = 'http://judas.eduzz.com';
        }

        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function send($messageData)
    {

        $body = json_encode($messageData);

        $result = $this->client->post(
            "{$this->baseUrl}/store",
            [
                "Content-Type"  => "application/json",
                "Accept"        => "application/json",
                "Authorization" => $this->token
            ],
            $body
        );

        return $result;
    }

}