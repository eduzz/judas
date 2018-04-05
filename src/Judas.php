<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Log\LoggerInterface;
use Eduzz\Judas\Log\JudasLogger;

class Judas
{
    private $hermes;

    private $logger;

    public function log($context, $messageData)
    {
        if(!($this->logger instanceof LoggerInterface)) {
            $this->setDefaultLogger();
        }

        $this->logger->info($context, $messageData);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function setDefaultLogger()
    {
        $this->logger = new JudasLogger();
    }

    public function setQueueConfig($config = null)
    {
        if(!$config || empty($config) || count($config) <= 0) {
            $this->hermes = new Hermes();
        }

        $this->hermes = new Hermes($config);
    }
}
