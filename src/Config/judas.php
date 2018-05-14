<?php

return array(
    'elastic_connection' => null,

    'queue_connection' => null,

    'environment' => 'production',

    'default_elastic_connection' => array(
        'host'                => 'localhost',
        'port'                => 9200,
        'username'            => 'elastic',
        'password'            => ''
    ),

    'default_queue_connection' => array(
        'host'                => 'localhost',
        'port'                => 5671,
        'username'            => 'guest',
        'password'            => 'guest'
    )
);
