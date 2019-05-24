<?php

namespace Eduzz\Judas\Tests;

use Eduzz\Judas\Logger;
use Mockery as M;

class LoggerTest extends BaseTest
{

    public function testLoggerCanBeCreated()
    {

        $clientMock = M::mock('\GuzzleHttp\Client');

        $logger = new Logger("http://test", "myToken", $clientMock);

        $this->assertInstanceOf('\Eduzz\Judas\Logger', $logger);
    }

    public function testCanLog()
    {

        $clientMock = M::mock('\GuzzleHttp\Client');

        $data = [
            "name" => "fulano"
        ];

        $clientMock->shouldReceive('post')
            ->with('http://test/store', [
                "future" => true,
                "headers" => [
                    "content-type"  => "application/json",
                    "Accept"        => "application/json",
                    "Authorization" => "myToken"
                ],
                "body" => json_encode($data)
            ])
            ->once()
            ->andReturn(M::mock('\GuzzleHttp\Message\FutureResponse'));

        $logger = new Logger("http://test", "myToken", $clientMock);

        $result = $logger->send($data);

        $this->assertInstanceOf('\GuzzleHttp\Message\FutureResponse', $result);
    }


}
