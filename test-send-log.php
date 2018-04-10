<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas();

$judas->setQueueConfig([
    'host' => 'localhost',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest'
]);

$judas->log(
    'cktsun.invoice.created',
    [
        'agent' => 'user',
        'event.data.id' => 123,
        'user.id' => 123,
        'user.name' => 'johndoe',
        'user.ip' => '45.4.72.82',
        'additional_data.amount' => 900.00
    ]
);
