<?php

namespace Eduzz\Judas\Tests;

use Eduzz\Judas\Validator\LogValidator;
use PHPUnit\Framework\TestCase;

class LogsValidatorTest extends TestCase
{
    public function testConstructorAndInstance()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'checkoutsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'data.id' => 2842,
                'date' => null,
                'user.id' => 123123,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ]
        );

        $this->assertInstanceOf(LogValidator::class, $logValidator);
    }

    public function testValidateWichShouldBeValid()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'checkoutsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'data.id' => 2842,
                'date' => null,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ]
        );

        $this->assertTrue($logValidator->isValid());
    }

    public function testLogValidatorValidateWichShouldBeInvalidBecauseDontHaveSomeIndex()
    {
        $logValidator = new LogValidator(
            [
                'AGENTSHOULDBEHERE' => 'user',
                'event.app' => 'checkoutsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'data.id' => 2842,
                'date' => null,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ]
        );

        $this->assertFalse($logValidator->isValid());
    }

    public function testLogValidatoreInvalidBecauseHaveInvalidChooseValue()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'HERESHOULDHAVEVALIDOPTION',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'data.id' => 2842,
                'date' => null,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ]
        );

        $this->assertFalse($logValidator->isValid());
    }

    public function testValidateWichShouldBeInvalidBecauseHaveInvalidType()
    {
        $logValidator = new LogValidator(
            [
                'agent' => 'user',
                'event.app' => 'checkoutsun',
                'event.module' => 'invoice',
                'event.action' => 'created',
                'data.id' => 'INVALIDTYPE',
                'date' => null,
                'user.id' => 12312,
                'user.name' => 'johndoe',
                'user.ip' => '127.0.0.1'
            ]
        );

        $this->assertFalse($logValidator->isValid());
    }


}
