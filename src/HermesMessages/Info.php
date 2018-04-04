<?php

namespace Eduzz\Judas\HermesMessages;

use Eduzz\Hermes\Message\AbstractMessage;

class Info extends AbstractMessage
{
    public function __construct()
    {
        parent::__construct('sun.user.created', ['id' => $id, 'message' => $message]);
    }
}
