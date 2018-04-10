<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas();

$judas->setElasticConfig([
    'host' => 'localhost',
    'port' => 5601,
    'user' => 'elastic',
    'pass' => ''
]);

$data = [
    'id' => 12,
    'name' => 'teste'
];

$judas->store('cktsun.invoice.created', json_encode($data));
