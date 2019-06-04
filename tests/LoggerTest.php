<?php

namespace Eduzz\Judas\Tests;

use Eduzz\Judas\Logger;
use Mockery as M;

class LoggerTest extends BaseTest
{

    public function testLoggerCanBeCreated()
    {

        $clientMock = M::mock('\Eduzz\Judas\Http\HttpClientInterface');

        $logger = new Logger("http://test", "myToken", $clientMock);

        $this->assertInstanceOf('\Eduzz\Judas\Logger', $logger);
    }

    public function testCanLog()
    {

        $clientMock = M::mock('\Eduzz\Judas\Http\HttpClientInterface');

        $data = [
            "name" => "fulano"
        ];

        $clientMock->shouldReceive('post')
            ->with(
                'http://test/store', [
                    "Content-Type"  => "application/json",
                    "Accept"        => "application/json",
                    "Authorization" => "myToken"
                ],
                json_encode($data)
            )
            ->once()
            ->andReturn(M::mock('\GuzzleHttp\Message\FutureResponse'));

        $logger = new Logger("http://test", "myToken", $clientMock);

        $result = $logger->send($data);

        $this->assertInstanceOf('\GuzzleHttp\Message\FutureResponse', $result);
    }


}
