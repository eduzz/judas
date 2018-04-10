<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas();

$judas->setKeeperConfig([
    'host' => 'localhost',
    'port' => 5601,
    'user' => 'elastic',
    'pass' => ''
]);

$data = [
    'id' => 12,
    'name' => 'teste'
];

$judas->store(json_encode($data));
