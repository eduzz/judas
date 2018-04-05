<?php

namespace Eduzz\Judas\Validator;

use Eduzz\Judas\Validator\Schemas;
use Adbar\Dot;

class LogValidator implements JsonValidatorInterface
{
    private $toValidate;

    private $schema;

    public function __construct($toValidate)
    {
        $this->setArrayToValidate($toValidate);
        $this->setSchema(Schemas::INFO);
    }

    public function isValid()
    {
        $decodedScheme = $this->schema;

        foreach ($this->schema as $key => $value) {
            $keyWithoutSymbols = str_replace('*', '', $key);

            if(strpos($key, '*') !== false) {
                if(!array_key_exists($keyWithoutSymbols, $this->toValidate)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function setArrayToValidate($toValidate)
    {
        if (!count($toValidate) || empty($toValidate)) {
            throw new \Error("Array to compare cannot be empty");
        }

        $this->toValidate = $toValidate;

        return $this;
    }

    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }
}
