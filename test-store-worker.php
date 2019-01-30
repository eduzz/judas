<?php

require_once __DIR__ . '/vendor/autoload.php';

use Eduzz\Judas\Judas;
use Eduzz\Hermes\Hermes;

$judas = new Judas();
$hermes = new Hermes();

$hermes->setConfig([
    'host' => '',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest'
]);

$judas->setKeeperConfig([
    'host' => '',
    'port' => null,
    'username' => '',
    'password' => ''
]);

$hermes->addListenerTo('myeduzzleg.user.login', function ($msg) use ($judas) {
    $judas->store(json_decode($msg->body));
});
