<?php

namespace Eduzz\Judas;

class Judas
{

    private $baseUrl;
    private $token;
    private $logger;

    private $environment = 'production';

    public function __construct($baseUrl, $token, $logger = null)
    {

        $this->token = $token;
        $this->baseUrl = $baseUrl;

        if (!$logger) {
            $logger = new Logger($this->baseUrl, $this->token);
        }

        $this->logger = $logger;
    }

    public function log($context, $messageData = null)
    {

        if (!$messageData) {
            $messageData = [];
        }

        $this->validateMessageData($messageData);

        $preparedData = $this->setDefaultParamsOnArray($messageData, $context, $this->environment);

        $dot = new \Adbar\Dot();
        $dot->set($preparedData);

        $finalData = $dot->get();

        return $this->logger->send($finalData);
    }

    public function error($context, \Exception $exception, $messageData = null)
    {

        if (!$messageData) {
            $messageData = [];
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

        $this->log($context, $messageData);
    }

    private function validateMessageData($messageData)
    {
        if (!is_array($messageData)) {
            throw new \UnexpectedValueException("messageData must be an array");
        }

        foreach ($messageData as $key => $value) {
            if (!preg_match('@^event\.@', $key)) {
                continue;
            }

            throw new \UnexpectedValueException("You cannot use the prefix 'event.' in your data");
        }
    }

    private function setDefaultParamsOnArray($array, $context, $environment)
    {
        $explodedContext = explode('.', $context);

        if (count($explodedContext) != 3) {
            throw new \OverflowException("Context must be in format app.module.action");
        }
        
        $time = microtime(true);
        $now = \DateTime::createFromFormat('U.u', $time);

        if (is_bool($now)) {
            $now = new \DateTime();
        }

        $array['event.date'] = preg_replace(
            '@\d{3}Z$@',
            'Z',
            $now->format("Y-m-d\TH:i:s.u\Z")
        );
        $array['event.context'] = $context;
        $array['event.environment'] = $environment;
        $array['event.app'] = $explodedContext[0];
        $array['event.module'] = $explodedContext[1];
        $array['event.action'] = $explodedContext[2];

        $host = gethostname();

        if ($host) {
            $array['event.hostname'] = $host;
        }

        return $array;
    }

    private function getServerVal($name)
    {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : '';
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

}
