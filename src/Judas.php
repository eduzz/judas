<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Log\LoggerInterface;
use Eduzz\Judas\Log\JudasLogger;
use Eduzz\Judas\LogKeeper\LogKeeperInterface;
use Eduzz\Judas\LogKeeper\JudasKeeper;

class Judas
{
    private $hermes;

    private $logger;

    private $queueConfig;

    private $logKeeper;

    private $keeperConfig;

    public function log($context, $messageData)
    {
        if (!($this->logger instanceof LoggerInterface)) {
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

        if ($this->queueConfig && !empty($this->queueConfig) && count($this->queueConfig) > 0) {
            $judasLogger->setQueueConfig($this->queueConfig);
        }

        return $judasLogger;
    }

    public function setQueueConfig($config = null)
    {
        if (!$config || empty($config) || count($config) <= 0 || !is_array($config)) {
            throw new \Error("Queue config cannot be empty");
        }

        $this->queueConfig = $config;
    }

    public function store($json)
    {
        if (!($this->logger instanceof LogKeeperInterface)) {
            $this->setLogKeeper($this->getDefaultLogKeeper());
        }

        $this->logKeeper->store($json);
    }

    public function setLogKeeper(LogKeeperInterface $logKeeper)
    {
        $this->logKeeper = $logKeeper;
    }

    private function getDefaultLogKeeper()
    {
        $judasKeeper = new JudasKeeper();

        if (!empty($this->keeperConfig)) {
            $judasKeeper->setElasticConfig($this->keeperConfig);
        }

        return $judasKeeper;
    }

    public function setKeeperConfig($config = null)
    {
        if (!is_array($config) || count($config) <= 0) {
            throw new \Error("Elastic config cannot be empty");
        }

        $this->keeperConfig = $config;
    }
}
