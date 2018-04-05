<?php

namespace Eduzz\Judas\Validator;

class Schemas
{
    const INFO = [
        '*agent' => ['choose', 'procedure', 'system', 'user', 'support'],
        '*event.app' => ['choose', 'checkoutsun', 'myeduzz', 'checkoutleg', 'myeduzzleg', 'nutror', 'next'],
        '*event.module' => ['type', 'expected' => 'string'],
        '*event.action' => ['type', 'expected' => 'string'],
        '*data.id' => ['type', 'expected' => 'integer'],
        '*date' => '*',
        'user.id' => ['type', 'expected' => 'integer'],
        'user.name' => ['type', 'expected' => 'string'],
        'user.ip' => [
            'regex',
            'pattern' => '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/'
        ],
        'additional_data' => '*' // Any other data goes here
    ];
}
