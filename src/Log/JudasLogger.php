<?php

namespace Eduzz\Judas\Log;

use Eduzz\Judas\Log\LoggerInterface;
use Eduzz\Judas\Validator\LogValidator;
use Eduzz\Judas\Validator\Schemas;
use Eduzz\Hermes\Hermes;
use Eduzz\Hermes\Message\AbstractMessage;
use Adbar\Dot;

class JudasLogger implements LoggerInterface
{
    private $message;

    private $context;

    public function info($context, $message)
    {
        $this->setMessage($message);
        $this->setContext($context);
    }

    public function setMessage($messageData)
    {
        $logValidator = new LogValidator($messageData, Schemas::INFO);

        if(!$logValidator->isValid()) {
            throw new \Error($logValidator->getLastValidationErrorMessage());
        }

        $this->message = $messageData;
    }

    private function getMessageAsJson()
    {
        $dot = new Dot($this->message);

        return $dot->toJson();
    }

    public function setContext($context)
    {
        if(empty($context)) {
            throw new \Error("Context cannot be empty");
        }

        $this->context = $context;
    }
}
