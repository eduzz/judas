<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas();

$judas->setKeeperConfig([
    'host' => 'localhost',
    'port' => 9200,
    'username' => 'elastic',
    'password' => ''
]);

$data = [
    'id' => 12,
    'name' => 'xpto',
    'index' => 'history'
];

$judas->store(json_encode($data));
