<?php

namespace Eduzz\Judas\Tests;

use Mockery as M;

use Eduzz\Judas\Judas;

class JudasTest extends BaseTest
{

    public function testShouldSetEnvironment()
    {

        $loggerMock = M::mock('Eduzz\Judas\Logger');

        $judas = new Judas("", "thetoken", $loggerMock);

        $this->assertEquals('production', $judas->getEnvironment());

        $judas->setEnvironment('wallawalla');

        $this->assertEquals('wallawalla', $judas->getEnvironment());
    }

    public function testJudasCanLog()
    {
        $loggerMock = M::mock('Eduzz\Judas\Logger');

        $loggerMock->shouldReceive('send')
            ->once()
            ->withArgs(function($data) {
                $this->assertEquals("fulano", $data["name"]);
                $this->assertEquals("test", $data["event"]["app"]);
                $this->assertEquals("module", $data["event"]["module"]);
                $this->assertEquals("xpto", $data["event"]["action"]);
                $this->assertEquals("production", $data["event"]["environment"]);
                $this->assertArrayHasKey("hostname", $data["event"]);
                $this->assertArrayHasKey("date", $data["event"]);


                return true;
            })
            ->andReturn(true);

        $judas = new Judas("", "thetoken", $loggerMock);

        $judas->log(
            "test.module.xpto",
            [
                "name" => "fulano"
            ]
        );

    }

    public function testJudasCanLogError()
    {
        $loggerMock = M::mock('Eduzz\Judas\Logger');

        $loggerMock->shouldReceive('send')
            ->once()
            ->withArgs(function($data) {

                $this->assertArrayHasKey("exception", $data);
                $this->assertEquals("pqp", $data["exception"]["message"]);

                return true;
            })
            ->andReturn(true);

        $judas = new Judas("", "thetoken", $loggerMock);

        $judas->error(
            "test.module.xpto",
            new \Exception("pqp"),
            [
                "name" => "fulano"
            ]
        );

    }

    public function testJudasCanLogErrorWithoutExtraData()
    {
        $loggerMock = M::mock('Eduzz\Judas\Logger');

        $loggerMock->shouldReceive('send')
            ->once()
            ->withArgs(function($data) {

                $this->assertArrayHasKey("exception", $data);
                $this->assertEquals("pqp", $data["exception"]["message"]);

                return true;
            })
            ->andReturn(true);

        $judas = new Judas("", "thetoken", $loggerMock);

        $judas->error(
            "test.module.xpto",
            new \Exception("pqp")
        );

    }

    public function testShouldValidateTheContext()
    {
        $loggerMock = M::mock('Eduzz\Judas\Logger');

        $loggerMock->shouldReceive('send')
            ->never();

        $judas = new Judas("", "thetoken", $loggerMock);

        $this->expectException('OverflowException');

        $judas->log('my.context');
    }


    public function testShouldNotAcceptInvalidData()
    {
        $loggerMock = M::mock('Eduzz\Judas\Logger');

        $loggerMock->shouldReceive('send')
            ->never();

        $judas = new Judas("", "thetoken", $loggerMock);

        $this->expectException('UnexpectedValueException');

        $judas->log('my.context', 'asd');
    }


    public function testShouldAllowEmptyData()
    {
        $loggerMock = M::mock('Eduzz\Judas\Logger');

        $loggerMock->shouldReceive('send')
            ->once()
            ->andReturn(true);

        $judas = new Judas("", "thetoken", $loggerMock);

        $result = $judas->log('my.module.event');

        $this->assertTrue($result);
    }

}
