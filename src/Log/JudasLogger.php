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

    public function info($context, $message)
    {
        $this->setContext($context);
        $this->setMessage($message);

        $this->setHermesInstance();

        $this->hermes->publish(
            new Info($context, $this->getMessageAsJson())
        );
    }

    public function setMessage($messageData)
    {
        $this->setDefaultElasticParamsOnArray($messageData, $this->context);

        $logValidator = new LogValidator($messageData, Schemas::INFO);

        if(!$logValidator->isValid()) {
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
        if(!$this->hermes instanceof Hermes) {
            $this->hermes = new Hermes();
        }
    }

    private function setDefaultElasticParamsOnArray(&$array, $context) {
        $array['event.date'] = str_replace('+00:00', 'Z', gmdate('c'));
        $explodedContext = explode('.', $this->context);

        $array['event.app'] = $explodedContext[0];
        $array['event.module'] = $explodedContext[1];
        $array['event.action'] = $explodedContext[2];
    }

    public function setContext($context)
    {
        if(empty($context)) {
            throw new \Error("Context cannot be empty");
        }

        $this->context = $context;
    }
}
