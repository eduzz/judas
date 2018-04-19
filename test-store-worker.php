<?php

require_once __DIR__ . '/vendor/autoload.php';

use Eduzz\Judas\Judas;
use Eduzz\Hermes\Hermes;

$judas = new Judas();
$hermes = new Hermes();

$hermes->setConfig([
    'host' => 'ec2-54-242-180-212.compute-1.amazonaws.com',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest'
]);

$judas->setKeeperConfig([
    'host' => 'search-eduzz-43w3dzztjdgcnhain5wjzdn3lu.us-east-1.es.amazonaws.com',
    'port' => null,
    'username' => '',
    'password' => ''
]);

$hermes->addListenerTo('myeduzzleg.user.login', function ($msg) use ($judas) {
    $judas->store(json_decode($msg->body));
});
