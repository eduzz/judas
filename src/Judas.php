<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Log\LoggerInterface;
use Eduzz\Judas\Log\JudasLogger;
use Eduzz\Judas\LogKeeper\LogKeeperInterface;
use Eduzz\Judas\LogKeeper\JudasKeeper;

class Judas
{
    private $logger;

    private $queueConfig;

    private $logKeeper;

    private $keeperConfig;

    public $environment = 'production';

    public function log($context, $messageData, $environment = null)
    {
        if (!($this->logger instanceof LoggerInterface)) {
            //@codeCoverageIgnoreStart
            $this->setLogger($this->getDefaultLogger());
            //@codeCoverageIgnoreEnd
        }

        $this->logger->info($context, $messageData, $environment ?? $this->environment);

        return $this;
    }

    public function error($context, \Exception $exception, $messageData = null)
    {

        if (!is_array($messageData)) {
            $messageData = array();
        }

        $messageData = $messageData + [
            'agent' => 'user',
            'exception.class' => get_class($exception),
            'exception.message' => $exception->getMessage(),
            'exception.file' => $exception->getFile(),
            'exception.line' => $exception->getLine(),
            'exception.code' => $exception->getCode(),
            'exception.stacktrace' => $exception->getTraceAsString(),
            'exception.request.uri' => $this->getServerVal('REQUEST_URI'),
            'exception.request.query_string' => $this->getServerVal('QUERY_STRING'),
            'exception.request.method' => $this->getServerVal('REQUEST_METHOD'),
            'exception.request.user_agent' => $this->getServerVal('HTTP_USER_AGENT')
        ];

        $this->log($context, $messageData, 'errors');
    }

    private function getServerVal($name)
    {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : '';
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Permite definir no Judas em qual indice ele enviará o log. 
     * Ex: $this->judas->setEnvironment('activities')->log($context, $data)
     * Gravará este log no índice history-activities
     *
     * @param string $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
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
