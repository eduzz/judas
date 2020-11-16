<?php

return [
    'elastic_connection' => [
        'host' => 'localhost',
        'port' => 9200,
        'username' => 'elastic',
        'password' => '',
    ],

    'queue_connection' => [
        'host' => 'localhost',
        'port' => 5672,
        'username' => 'guest',
        'password' => 'guest',
        'vhost' => '/',
        'connection_name' => null,
    ],

    'environment' => 'development',
];
