<?php

namespace Eduzz\Judas\Validator;

class LogsValidator implements JsonValidatorInterface
{
    private $arrayToValidate;

    private $scheme;

    public function __construct($arrayToValidate, $scheme)
    {
        $this->setArray($arrayToValidate);
        $this->setScheme($scheme);
    }

    public function validate()
    {
        $decodedScheme = json_decode($this->scheme);

        // TODO: Compare array with json using the judas/log_scheme_base.php as example
    }

    public function setArray($array)
    {
        if(!count($array) || empty($array)) {
            throw new \Error("Array to compare cannot be empty");
        }

        $this->array = $array;

        return $this;
    }

    public function setScheme($scheme)
    {
        if(empty($scheme)) {
            throw new \Error("Json schema cannot be empty");
        }

        $this->scheme = $scheme;

        return $this;
    }
}
