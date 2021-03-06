<?php

namespace Eduzz\Judas\Validator;

use Eduzz\Judas\Validator\Schemas;
use Adbar\Dot;

class LogValidator implements JsonValidatorInterface
{
    private $toValidate;

    private $schema;

    private $lastValidationErrorMessage;

    public function __construct($toValidate, $schema)
    {
        $this->setArrayToValidate($toValidate);
        $this->setSchema($schema);
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
                    $this->setValidationErrorMessage("The attribute '{$keyWithoutSymbols}' is required in the message.");
                    return false;
                }
            }

            if (array_key_exists($keyWithoutSymbols, $this->toValidate)) {
                if (is_array($value)) {
                    $type = $value[0];
                    unset($value[0]);

                    if ($type == 'choose') {
                        if (!in_array($this->toValidate[$keyWithoutSymbols], $value)) {
                            $this->setValidationErrorMessage("Value {$this->toValidate[$keyWithoutSymbols]} is invalid, expecting one of these: " . $this->convertArrayToString($value));

                            return false;
                        }
                    }

                    if ($type == 'regex') {
                        if (!preg_match($value['pattern'], $this->toValidate[$keyWithoutSymbols])) {
                            $this->setValidationErrorMessage("Value " . $this->toValidate[$keyWithoutSymbols] . " is invalid, expect someting like: " . $value['example']);

                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    public function convertArrayToString($array)
    {
        $arrayString = "[";

        foreach ($array as $key => $value) {
            $arrayString .= "'" . $value . "'";

            if ($value != end($array)) {
                $arrayString .= ",";
            }
        }

        $arrayString .= "]";

        return $arrayString;
    }

    public function getLastValidationErrorMessage()
    {
        return $this->lastValidationErrorMessage;
    }

    private function setValidationErrorMessage($message)
    {
        $this->lastValidationErrorMessage = $message;
    }

    public function setArrayToValidate($toValidate)
    {
        if (!count($toValidate) || empty($toValidate)) {
            throw new \Exception("Array to compare cannot be empty");
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
