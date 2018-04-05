<?php

namespace Eduzz\Judas\HermesMessages;

use Eduzz\Hermes\Message\AbstractMessage;

class Info extends AbstractMessage
{
    public function __construct($context, $payload)
    {
        parent::__construct($context, $payload);
    }
}
