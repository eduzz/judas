<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas("http://localhost:4000", "asdfgh");

$result = $judas->log(
    'test.user.login',
    [
        'agent' => 'user',
        'data.id' => 999,
        'user.id' => 999,
        'user.name' => "angelorodriigo.rs@gmail.com",
        'user.ip' => "127.0.0.1",
        'additional_data.status' => 'success'
    ]
);
$result->then(function($result) {
    var_dump('request sent', $result);
}, function($err) {
    var_dump('request error', $err);
});

var_dump("complete");
