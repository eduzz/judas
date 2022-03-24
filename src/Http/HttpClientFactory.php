<?php

namespace Eduzz\Judas\Http;

class HttpClientFactory
{

    public function createHttpClient() {

        if (class_exists('\GuzzleHttp\Client')) {

            $class = '\GuzzleHttp\Client';

            $object = new $class();

            $version = defined("$class::VERSION") ? $object::VERSION : (defined("$class::MAJOR_VERSION") ? $object::MAJOR_VERSION : null);

            if (preg_match('@^5@', $version)) {
                return new Guzzle5Client();
            }

            if (preg_match('@^6@', $version)) {
                return new Guzzle6Client();
            }

            if (preg_match('@^7@', $version)) {
                return new Guzzle7Client();
            }
        }

        throw new \RuntimeException("We need guzzle 5, 6 or 7");

    }


}