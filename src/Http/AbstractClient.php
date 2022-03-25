<?php

namespace Eduzz\Judas\Http;

abstract class AbstractClient
{
    protected function isCli()
    {
        if (defined('STDIN')) {
            return true;
        }

        return (
            empty($_SERVER['REMOTE_ADDR'])
            && !isset($_SERVER['HTTP_USER_AGENT'])
            && count($_SERVER['argv']) > 0
        );
    }

    protected function await($promise)
    {
        if ($this->isCli()) {
            return $promise->wait();
        }
        
        register_shutdown_function(function() use ($promise) {
            $promise->wait();
        });
    } 
}