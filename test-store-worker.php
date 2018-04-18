<?php

require_once __DIR__ . '/vendor/autoload.php';

use Eduzz\Judas\Judas;
use Eduzz\Hermes\Hermes;

$judas = new Judas();
$hermes = new Hermes();

$hermes->setConfig([
    'host' => 'localhost',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest'
]);

$judas->setKeeperConfig([
    'host' => 'localhost',
    'port' => 9200,
    'user' => 'elastic',
    'pass' => ''
]);

$hermes->addListenerTo('myeduzzleg.user.login', function ($msg) use ($judas) {
    $judas->store(json_decode($msg->body));
});
