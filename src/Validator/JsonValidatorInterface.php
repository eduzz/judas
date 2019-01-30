<?php

namespace Eduzz\Judas\Validator;

interface JsonValidatorInterface
{
    public function __construct($toValidate, $schema);

    public function isValid();

    public function setArrayToValidate($toValidate);

    public function setSchema($schema);
}
