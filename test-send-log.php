<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas();

// Logando tentativa de login com judas
$judas->setQueueConfig([
    'host' => 'ec2-54-242-180-212.compute-1.amazonaws.com',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest'
]);

$judas->log(
    'myeduzzleg.user.login',
    [
        'agent' => 'user',
        'event.data.id' => 999,
        'user.id' => 999,
        'user.name' => "angelorodriigo.rs@gmail.com",
        'user.ip' => "127.0.0.1",
        'additional_data.status' => 'success'
    ]
);
