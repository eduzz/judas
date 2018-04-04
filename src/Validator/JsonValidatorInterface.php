<?php

namespace Eduzz\Judas\Validator;

interface JsonValidatorInterface
{
    public function __construct($arrayToValidate, $scheme);

    public function validate();

    public function setArray($array);

    public function setScheme($scheme);
}
