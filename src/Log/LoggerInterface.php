<?php

namespace Eduzz\Judas\Log;

interface LoggerInterface
{
    public function info($message, $context = array());
}
