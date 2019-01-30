<?php

namespace Eduzz\Judas\HermesMessages;

use Eduzz\Hermes\Message\AbstractMessage;

class Info extends AbstractMessage
{
    public function __construct($payload)
    {
        parent::__construct('judas.store.info', $payload);
    }
}
