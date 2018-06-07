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
        ]
    ];
}
