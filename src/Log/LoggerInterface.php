<?php

namespace Eduzz\Judas\Log;

interface LoggerInterface
{
    public function info($context, $message, $dev);
}
