    <?php

/*
    Symbols

    * = required
*/

$array = [
    '*agent' => ['procedure', 'system', 'user', 'support'],
    '*event.app' => ['checkoutsun', 'myeduzz', 'checkoutleg', 'myeduzzleg', 'nutror', 'next'],
    '*event.module' => 'string',
    '*event.action' => 'string',
    '*data.id' => 'int',
    '*date' => '??',
    'user.id' => 'int',
    'user.name' => 'string',
    'user.ip' => ['regex', '^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$'],
    'additional_data' => null// Any other data goes here
];
