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

$hermes->addListenerTo('myeduzzleg.user.login', function ($msg) use ($judas) {
    var_dump(json_decode($msg->body));
});
