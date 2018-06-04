<?php

namespace Eduzz\Judas\Validator;

class Schemas
{
    public static $INFO = [
        '*agent' => ['choose', 'procedure', 'system', 'user', 'support'],
        '*event.app' => ['choose', 'cktsun', 'myeduzz', 'cktleg', 'myeduzzleg', 'nutror', 'next', 'backoffice'],
        '*event.module' => '*',
        '*event.action' => '*',
        '*event.date' => [
            'regex',
            'pattern' => '/^\b[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z\b/',
            'example' => '2018-04-06T14:10:57Z'
        ],
        'user.id' => '*',
        'user.name' => '*',
        'user.ip' => [
            'regex',
            'pattern' => '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',
            'example' => '123.123.123.123'
        ],
        'additional_data' => '*' // Any other data goes here
    ];
}
