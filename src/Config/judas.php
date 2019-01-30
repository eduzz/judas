<?php

return array(
    'elastic_connection' => array(
        'host'                => 'localhost',
        'port'                => 9200,
        'username'            => 'elastic',
        'password'            => ''
    ),

    'queue_connection' => array(
        'host'                => 'localhost',
        'port'                => 5672,
        'username'            => 'guest',
        'password'            => 'guest',
        'vhost'               => '/'
    ),

    'environment' => 'development'
);
