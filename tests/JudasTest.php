<?php

namespace Eduzz\Judas\Tests;

use Mockery as M;

use Eduzz\Judas\Judas;
use Eduzz\Judas\Log\JudasLogger;
use Eduzz\Judas\LogKeeper\LogKeeperInterface;

class JudasTest extends BaseTest
{
    public function testJudasCanBeInstantiated()
    {
        $args = [
            'app.module.action',
            [
                'id' => 1,
                'message' => 'Nothing to see here.'
            ],
            'development'
        ];

        $judasLoggerMock = M::mock(JudasLogger::class)
            ->shouldReceive('info')
            ->withArgs($args)
            ->andReturnNull()
            ->getMock();

        $judas = new Judas();

        $judas->environment = 'development';

        $judas->setLogger($judasLoggerMock);

        $judas->log(
            $args[0],
            $args[1]
        );

        $this->assertSame(
            $judas,
            $judas->log(
                $args[0],
                $args[1]
            )
        );
    }

    public function testJudasShouldSetQueueConfig()
    {
        $args = [
            'host' => 'localhost',
            'port' => 5672,
            'username' => 'guest',
            'password' => 'guest'
        ];

        $judas = new Judas();

        $this->assertSame($judas, $judas->setQueueConfig($args));
    }

    public function testJudasShouldSetEmptyArrayConfigAndThrowError()
    {
        $this->expectException(\Error::class);

        $args = null; // Empty to force error

        $judas = new Judas();

        $judas->setQueueConfig($args);
    }

    public function testJudasShouldStoreALog()
    {
        $jsonArgument = json_encode(
            [
            'id' => 1,
            'message' => 'Nothing to see here.',
            'index' => 'history'
            ]
        );

        $return = '{"_index":"history","_type":"default","_id":"62V642IBayGLmdFUVWTo","_version":1,"result":"created","_shards":{"total":1,"successful":1,"failed":0},"_seq_no":0,"_primary_term":1}';

        $judasKeeperMock = M::mock(LogKeeperInterface::class)
            ->shouldReceive('store')
            ->with($jsonArgument)
            ->andReturn($return)
            ->getMock();

        $judas = new Judas();
        $judas->setLogKeeper($judasKeeperMock);

        $response = $judas->store($jsonArgument);

        $this->assertEquals($return, $response);
    }

    public function testJudasShouldStoreLog()
    {
        $judas = new Judas();

        $judas->setKeeperConfig(
            [
            'host' => '127.0.0.1',
            'port' => '9200',
            'username' => 'elastic',
            'password' => ''
            ]
        );

        $response = $judas->store(
            json_encode(
                [
                'agent' => 'user',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => 2842,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1',
                'index' => 'history'
                ]
            )
        );

        $this->assertInternalType('array', $response);
    }

    public function testJudasShouldSetKeeperConfig()
    {
        $args = [
            'host' => 'localhost',
            'port' => 9200,
            'user' => 'elastic',
            'password' => 'elastic'
        ];

        $judas = new Judas();
        $response = $judas->setKeeperConfig($args);

        $this->assertSame($judas, $response);
    }

    public function testJudasShouldSetEmptyKeeperConfigAndThrowError()
    {
        $this->expectException(\Error::class);

        $args = null; // Empty to force error

        $judas = new Judas();
        $judas->setKeeperConfig($args);
    }
}
