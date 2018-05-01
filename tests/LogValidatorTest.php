<?php

namespace Eduzz\Judas\Tests;

use Mockery as M;

use Eduzz\Judas\Validator\LogValidator;
use Eduzz\Judas\Validator\Schemas;

class LogValidatorTest extends BaseTest
{
    public function testConstructorAndInstance()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'cktsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'data.id' => 2842,
                'date' => null,
                'user.id' => 123123,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ],
            Schemas::$INFO
        );

        $this->assertInstanceOf(LogValidator::class, $logValidator);
    }

    public function testValidateWichShouldBeValid()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'cktsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => 2842,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ],
            Schemas::$INFO
        );

        $this->assertTrue($logValidator->isValid());
    }

    public function testValidateWichShouldBeInvalidBecauseDontHaveSomeIndex()
    {
        $logValidator = new LogValidator(
            [
                'AGENTSHOULDBEHERE' => 'user',
                'event.app' => 'cktsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => 2842,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ],
            Schemas::$INFO
        );

        $this->assertFalse($logValidator->isValid());
    }

    public function testeInvalidBecauseHaveInvalidChooseValue()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'HERESHOULDHAVEAVALIDOPTION',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => 2842,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ],
            Schemas::$INFO
        );

        $this->assertFalse($logValidator->isValid());
    }

    public function testValidateWichShouldBeInvalidBecauseDontPassInRegex()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'cktsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'event.date' => 'THISDONTWILLPASS',
                'event.data.id' => 1,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ],
            Schemas::$INFO
        );

        $this->assertFalse($logValidator->isValid());
    }

    public function testValidateShouldReturnLastErrorMessage() {
        $logValidator = new LogValidator(
            [
                'agent' => 'INVALID_AGENT',
                'event.app' => 'cktsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => 3,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ],
            Schemas::$INFO
        );

        // Error message:  "Type of INVALIDTYPE is invalid, expecting: integer on event.data.id"
        $logValidator->isValid();

        $this->assertEquals(
            "Value INVALID_AGENT is invalid, expecting one of these: ['procedure','system','user','support']",
            $logValidator->getLastValidationErrorMessage()
        );
    }

    public function testValidateWithoutSchema() {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'cktsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'event.date' => '2018-04-06T14:10:57Z',
                'event.data.id' => 'INVALIDTYPE',
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ],
            []
        );

        $this->assertTrue($logValidator->isValid());
    }

    public function testValidateShouldSetAnEmptyArrayToValidateAndThrowError() {
        $this->expectException(\Error::class);

        $logValidator = new LogValidator(
            [],
            []
        );

    }
}
