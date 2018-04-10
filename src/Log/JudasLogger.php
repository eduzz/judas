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

    private $hermes;

    private $queueConfig;

    public function info($context, $message)
    {
        $this->setContext($context);
        $this->setMessage($message, Schemas::INFO, 'history');

        $this->setHermesInstance();

        $this->hermes->publish(
            new Info($context, $this->getMessageAsJson())
        );
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

        return $dot->toJson();
    }

    private function setHermesInstance()
    {
        if (!$this->hermes instanceof Hermes) {
            if ($this->queueConfig && !empty($this->queueConfig) && count($this->queueConfig) > 0) {
                $this->hermes = new Hermes($this->queueConfig);
            } else {
                $this->hermes = new Hermes();
            }
        }
    }

    public function setQueueConfig($config = null)
    {
        if (!$config || empty($config) || count($config) <= 0) {
            throw new \Error("Config cannot be empty");
        }

        $this->queueConfig = $config;
    }

    private function setDefaultParamsOnArray(&$array, $context, $index)
    {
        $array['event.date'] = str_replace('+00:00', 'Z', gmdate('c'));
        $array['index'] = $index;
        $explodedContext = explode('.', $this->context);

        $array['event.app'] = $explodedContext[0];
        $array['event.module'] = $explodedContext[1];
        $array['event.action'] = $explodedContext[2];
    }

    public function setContext($context)
    {
        if (empty($context)) {
            throw new \Error("Context cannot be empty");
        }

        $this->context = $context;
    }
}
