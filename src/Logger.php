<?php

namespace Eduzz\Judas;

use GuzzleHttp\Client;

class Logger
{

    /* @var \GuzzleHttp\Client */
    private $client;
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token, $client = null)
    {
        if (!$client) {
            $client = new Client();
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

        $result = $this->client->post("{$this->baseUrl}/store", [
            "future" => true,
            "headers" => [
                "content-type"  => "application/json",
                "Accept"        => "application/json",
                "Authorization" => $this->token
            ],
            "body" => $body
        ]);

        return $result;
    }

}