<?php

namespace Eduzz\Judas\Tests;

use Mockery as M;

use Eduzz\Judas\Judas;
use Eduzz\Judas\Log\JudasLogger;
use Eduzz\Hermes\Hermes;
use Eduzz\Hermes\Message\AbstractMessage;
use Eduzz\Judas\HermesMessages\Info;

class JudasLoggerTest extends BaseTest
{
    public function testJudasLoggerShouldLogAnInfo() 
    {
        $args = [
            'topic' => 'cktsun.module.action',
            'message' => [
                'agent' => 'user',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => 2842,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ]
        ];

        $hermesMock = M::mock(Hermes::class)
            ->shouldReceive('publish')
            ->withArgs(
                function ($obj) {
                    return $obj instanceof Info;
                }
            )
            ->andReturnUsing(
                function ($obj) use ($args) {
                    $obj->topic = $args['topic'];
                    $obj->message = $args['message'];
                    return $obj;
                }
            )
            ->getMock();

        $judasLogger = new JudasLogger();

        $judasLogger->setQueueManager($hermesMock);

        $this->assertSame(
            $judasLogger,
            $judasLogger->info(
                $args['topic'],
                $args['message']
            )
        );
    }

    public function testJudasLoggerShouldLogAnInfoWithInvalidMessageAndFail() 
    {
        $this->expectException(\Error::class);

        $args = [
            'topic' => 'cktsun.module.action',
            'message' => [
                'agent' => 'INVALID_AGENT',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => '2842',
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ]
        ];

        $hermesMock = M::mock(Hermes::class)
            ->shouldReceive('publish')
            ->withArgs(
                function ($obj) {
                    return $obj instanceof Info;
                }
            )
            ->andReturnUsing(
                function ($obj) use ($args) {
                    $obj->topic = $args['topic'];
                    $obj->message = $args['message'];
                    return $obj;
                }
            )
            ->getMock();

        $judasLogger = new JudasLogger();

        $judasLogger->setQueueManager($hermesMock);

        $judasLogger->info(
            $args['topic'],
            $args['message']
        );
    }

    public function testJudasShouldSetConfig() 
    {
        $config = [
            'host' => 'localhost',
            'port' => 5672,
            'username' => 'guest',
            'password' => 'guest'
        ];

        $judasLogger = new JudasLogger();

        $this->assertSame($judasLogger, $judasLogger->setQueueConfig($config));
    }

    public function testJudasShouldSetEmptyConfigAndFail() 
    {
        $this->expectException(\Error::class);

        $config = null;

        $judasLogger = new JudasLogger();

        $judasLogger->setQueueConfig($config);
    }

    public function testJudasShouldSetEmptyContextAndFail() 
    {
        $this->expectException(\Error::class);

        $context = null;

        $judasLogger = new JudasLogger();

        $judasLogger->setContext($context);
    }
}
