<?php

namespace Eduzz\Judas\Http;

class HttpClientFactory
{

    public function createHttpClient() {

        if (class_exists('\GuzzleHttp\Client')) {

            $class = '\GuzzleHttp\Client';

            $object = new $class();

            if (preg_match('@^5@', $object::VERSION)) {
                return new Guzzle5Client();
            }

            if (preg_match('@^6@', $object::VERSION)) {
                return new Guzzle6Client();
            }
        }

        throw new \RuntimeException("We need guzzle 5 or 6");

    }


}