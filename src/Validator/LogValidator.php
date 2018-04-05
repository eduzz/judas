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
        if (count($this->schema) <= 0) {
            return true;
        }

        foreach ($this->schema as $key => $value) {
            $keyWithoutSymbols = str_replace('*', '', $key);

            if (strpos($key, '*') !== false) {
                if (!array_key_exists($keyWithoutSymbols, $this->toValidate)) {
                    return false;
                }
            }

            if (array_key_exists($keyWithoutSymbols, $this->toValidate)) {
                if (is_array($value)) {
                    $type = $value[0];
                    unset($value[0]);

                    if ($type == 'choose') {
                        if (!in_array($this->toValidate[$keyWithoutSymbols], $value)) {
                            return false;
                        }
                    }

                    if ($type == 'regex') {
                        if (!preg_match($value['pattern'], $this->toValidate[$keyWithoutSymbols])) {
                            return false;
                        }
                    }

                    if ($type == 'type') {
                        if (gettype($this->toValidate[$keyWithoutSymbols]) != $value['expected']) {
                            return false;
                        }
                    }
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
