<?php

namespace Eduzz\Judas\Log;

use Eduzz\Judas\Log\LoggerInterface;
use Eduzz\Judas\Validator\LogValidator;
use Eduzz\Judas\Validator\Schemas;
use Eduzz\Judas\HermesMessages\Info;
use Eduzz\Hermes\Hermes;
use Eduzz\Hermes\Message\AbstractMessage;
use Adbar\Dot;

class JudasLogger implements LoggerInterface
{
    private $message;

    private $context;

    private $queueManager;

    private $queueConfig;

    public function info($context, $message, $environment)
    {
        $this->setContext($context);

        $index = $this->getIndexForEnvironment($environment);

        $this->setMessage($message, Schemas::$INFO, $index);

        if (!($this->queueManager instanceof Hermes)) {
            //@codeCoverageIgnoreStart
            $this->setQueueManager($this->getDefaultQueueManager());
            //@codeCoverageIgnoreEnd
        }

        $this->queueManager->publish(
            new Info($this->getMessageAsJson())
        );

        return $this;
    }

    public function getIndexForEnvironment($environment) {
        $index = 'history';

        if($environment != 'production') {
            $index = $index . '-' . $environment;
        }

        return $index;
    }

    public function setMessage($messageData, $schema, $index)
    {
        $this->setDefaultParamsOnArray($messageData, $this->context, $index);

        $logValidator = new LogValidator($messageData, $schema);

        if (!$logValidator->isValid()) {
            throw new \Error($logValidator->getLastValidationErrorMessage());
        }

        $this->message = $messageData;
    }

    private function getMessageAsJson()
    {
        $dot = new Dot();

        foreach ($this->message as $key => $value) {
            $dot->set($key, $value);
        }

        return $dot->all();
    }

    public function setQueueManager($manager)
    {
        $this->queueManager = $manager;

        return $this;
    }

    //@codeCoverageIgnoreStart
    private function getDefaultQueueManager()
    {
        if ($this->queueConfig && !empty($this->queueConfig) && count($this->queueConfig) > 0) {
            $queueManager = new Hermes($this->queueConfig);
        } else {
            $queueManager = new Hermes();
        }

        return $queueManager;
    }
    //@codeCoverageIgnoreEnd

    public function setQueueConfig($config = null)
    {
        if (!$config || empty($config) || count($config) <= 0) {
            throw new \Error("Config cannot be empty");
        }

        $this->queueConfig = $config;

        return $this;
    }

    private function setDefaultParamsOnArray(&$array, $context, $index)
    {
        $array['event.date'] = str_replace('+00:00', 'Z', gmdate('c'));
        $array['index'] = $index;
        $explodedContext = explode('.', $this->context);

        $array['event.app'] = $explodedContext[0];
        $array['event.module'] = $explodedContext[1];
        $array['event.action'] = $explodedContext[2];

        return $this;
    }

    public function setContext($context)
    {
        if (empty($context)) {
            throw new \Error("Context cannot be empty");
        }

        $this->context = $context;
    }
}
