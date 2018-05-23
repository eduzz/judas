<?php

namespace Eduzz\Judas\Tests;

use Mockery as M;

use Eduzz\Judas\Judas;
use Eduzz\Judas\LogKeeper\JudasKeeper;

class JudasKeeperTest extends BaseTest
{
    public function testJudasKeeperShouldSetConfig()
    {
        $args = [
            'host' => 'localhost',
            'port' => 9200,
            'username' => 'elastic',
            'password' => ''
        ];

        $judasKeeper = new JudasKeeper();

        $this->assertSame(
            $judasKeeper,
            $judasKeeper->setElasticConfig($args)
        );
    }

    public function testJudasKeeperShouldSetEmptyConfigAndFail()
    {
        $this->expectException(\InvalidArgumentException::class);

        $args = null;

        $judasKeeper = new JudasKeeper();

        $judasKeeper->setElasticConfig($args);
    }

    public function testJudasKeeperShouldStoreLog() 
    {
        $judasKeeper = new JudasKeeper();

        $response = $judasKeeper->store(
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

        $this->assertFalse($response);
    }

    public function testJudasKeeperShouldSetConfigWithoutHostPropertyAndFail()
    {
        $this->expectException(\InvalidArgumentException::class);

        $args = [
            'this_is_not_the_host' => 'localhost',
            'port' => 9200,
            'username' => 'elastic',
            'password' => ''
        ];

        $judasKeeper = new JudasKeeper();

        $judasKeeper->setElasticConfig($args);
    }

    public function testJudasKeeperShouldSetConfigWithWrongHostPropertyTypeAndFail()
    {
        $this->expectException(\InvalidArgumentException::class);

        $args = [
            'host' => 2, // This is not a string :(
            'port' => 9200,
            'username' => 'elastic',
            'password' => ''
        ];

        $judasKeeper = new JudasKeeper();

        $judasKeeper->setElasticConfig($args);
    }

    public function testJudasKeeperShouldReturnAValueFromAJsonString()
    {
        $args = [
            'attribute',
            json_encode(
                [
                'attribute' => 'This is the expected value.',
                'not_the_attribute' => 1
                ]
            )
        ];

        $judasKeeper = new JudasKeeper();

        $this->assertEquals("This is the expected value.", $judasKeeper->getAttributeValueFromJson($args[0], $args[1]));
    }

    public function testJudasKeeperShouldFailWhenTryToReturnValueFromJsonWithNotExistentAttribute()
    {
        $this->expectException(\Error::class);

        $args = [
            'attribute',
            json_encode(
                [
                'not_the_attribute' => 1
                ]
            )
        ];

        $judasKeeper = new JudasKeeper();

        $judasKeeper->getAttributeValueFromJson($args[0], $args[1]);
    }
}
