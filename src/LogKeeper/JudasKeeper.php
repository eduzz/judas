<?php

namespace Eduzz\Judas\LogKeeper;

use Eduzz\Judas\LogKeeper\LogKeeperInterface;

class JudasKeeper implements LogKeeperInterface
{
    private $config;

    public function __construct()
    {
    }

    public function store($json)
    {
        if (empty($this->config)) {
            $this->setElasticConfig($this->getDefaultElasticConfig());
        }

        $this->send($json);
    }

    private function send($json)
    {
        $index = $this->getAttributeValueFromJson('index', $json);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getElasticUrlForIndex($index));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $headers = [];
        $headers[] = 'Content-Type: application/json';

        if ($this->config['user'] != '' && $this->config['user']) {
            $token = base64_encode($this->config['user'] . ':' . $this->config['pass']);

            $headers[] = 'Authorization:Basic ' . $token;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        var_dump(curl_exec($ch));
    }

    private function getAttributeValueFromJson($attribute, $json)
    {
        $decoded = json_decode($json);

        if (!property_exists($decoded, $attribute)) {
            throw new \Error("Attribute {$attribute} not found in {$json}");
        }

        return $decoded->{$attribute};
    }

    private function getElasticUrlForIndex($index)
    {
        $url = 'http://' . $this->config['host'] . ':' .
            $this->config['port'] . '/' .
            $index . '/default';

        echo $url;

        return $url;
    }

    private function getDefaultElasticConfig()
    {
        return [
            'host' => 'localhost',
            'port' => 9200,
            'user' => 'elastic',
            'pass' => ''
        ];
    }

    public function setElasticConfig($config)
    {
        if (!is_array($config)) {
            throw new \InvalidArgumentException("Config must be an array, {$config} given");
        }

        if (!array_key_exists('host', $config)) {
            throw new \InvalidArgumentException("Config must have host attribute");
        }
        if (gettype($config['host']) != 'string') {
            throw new \InvalidArgumentException("Host must be an string, {$config['host']} given");
        }

        if (!array_key_exists('port', $config)) {
            throw new \InvalidArgumentException("Config must have port attribute");
        }
        if (gettype($config['port']) != 'integer') {
            throw new \InvalidArgumentException("Port must be an integer, {$config['port']} given");
        }

        $this->config = $config;
    }
}
