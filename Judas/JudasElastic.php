<?php
namespace Judas;

class JudasElastic
{
    private $connection;

    public function __construct($host = '')
    {
        $this->connection = ($host == '') ? $this->getDefaultHost() : $host;
    }

    public function getDefaultHost()
    {
        return (object) ['host' => 'localhost', 'port' => '9201', 'scheme' => 'http', 'user' => '', 'pass' => ''];
    }

    public function put($type, $index, $data)
    {
        $ch = curl_init();
        $url = $this->connection->scheme.'://'.$this->connection->host.':'.$this->connection->port.'/'.$index.'/'.$type;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        if ($this->connection->user != '' && $this->connection->pass != '') {
            $token = base64_encode($this->connection->user.':'.$this->connection->pass);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Authorization:Basic '.$token]);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $response=curl_exec($ch);

        return $response;
    }
}
