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

    public $environment = 'production';

    public function log($context, $messageData)
    {
        if (!($this->logger instanceof LoggerInterface)) {
            //@codeCoverageIgnoreStart
            $this->setLogger($this->getDefaultLogger());
            //@codeCoverageIgnoreEnd
        }

        $this->logger->info($context, $messageData, $this->environment);

        return $this;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    //@codeCoverageIgnoreStart
    private function getDefaultLogger()
    {
        $judasLogger = new JudasLogger();

        if ($this->queueConfig && !empty($this->queueConfig) && count($this->queueConfig) > 0) {
            $judasLogger->setQueueConfig($this->queueConfig);
        }

        return $judasLogger;
    }
    //@codeCoverageIgnoreEnd

    public function setQueueConfig($config = null)
    {
        if (!$config || empty($config) || count($config) <= 0 || !is_array($config)) {
            throw new \Exception("Queue config cannot be empty");
        }

        $this->queueConfig = $config;

        return $this;
    }

    public function store($json)
    {
        if (!($this->logKeeper instanceof LogKeeperInterface)) {
            $this->setLogKeeper($this->getDefaultLogKeeper());
        }

        return $this->logKeeper->store($json);
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
            throw new \Exception("Elastic config cannot be empty");
        }

        $this->keeperConfig = $config;

        return $this;
    }
}
