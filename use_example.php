<?php

$judas = new Judas($rabbitMQCredentials);

$judas->log(
    'sun.invoice.created',
    [
        'agent' => 'sys',
        'invoice.id' => 102002,
        'invoice.amount' => 2320.00,
        'user.id' => 815959,
        'user.name' => 'JEZAO',
        'cassetinho' => 'XPTO'
    ]
);
