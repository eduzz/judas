<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Log\LoggerInterface;
use Eduzz\Judas\Log\JudasLogger;

class Judas
{
    private $hermes;

    private $logger;

    private $queueConfig;

    private $elasticConfig;

    public function log($context, $messageData)
    {
        if(!($this->logger instanceof LoggerInterface)) {
            $this->setLogger($this->getDefaultLogger());
        }

        $this->logger->info($context, $messageData);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function getDefaultLogger()
    {
        $judasLogger = new JudasLogger();

        if($this->queueConfig && !empty($this->queueConfig) && count($this->queueConfig) > 0) {
            $judasLogger->setQueueConfig($this->queueConfig);
        }

        return $judasLogger;
    }

    public function setQueueConfig($config = null)
    {
        if(!$config || empty($config) || count($config) <= 0) {
            throw new \Error("Queue config cannot be empty");
        }

        $this->queueConfig = $config;
    }

    public function store($context, $jsonData) {
        if(empty($jsonData) || !$jsonData) {
            throw new \Error("Data for elastic cannot be empty");
        }

        if(empty(!$context) || !$context) {
            throw new \Error("Context cannot be empty");
        }

        $keeper = new LogKeeper();
        $keeper->store($context, $jsonData);
    }

    public function setElasticConfig($config = null) {
        if(!$config || empty($config) || count($config) <= 0) {
            throw new \Error("Elastic config cannot be empty");
        }

        $this->elasticConfig = $config;
    }
}
