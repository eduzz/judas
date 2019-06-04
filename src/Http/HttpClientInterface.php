<?php

namespace Eduzz\Judas\Http;


interface HttpClientInterface
{

    public function post($url, $headers, $body);

}