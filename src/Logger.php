<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Http\HttpClientFactory;

class Logger
{

    /* @var \Eduzz\Judas\Http\HttpClientInterface */
    private $client;
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token, $client = null)
    {

        if (!$client) {
            $factory = new HttpClientFactory();
            $client = $factory->createHttpClient();
        }

        $this->client = $client;

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