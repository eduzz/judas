<?php

require_once __DIR__ . '/vendor/autoload.php';

use Eduzz\Judas\Judas;
use Eduzz\Hermes\Hermes;

$judas = new Judas();
$hermes = new Hermes();

$hermes->addListenerTo('cktsun.invoice.created', function($msg) use ($judas) {
    $judas->store(json_decode($msg->body));
});

// Create a worker test here
