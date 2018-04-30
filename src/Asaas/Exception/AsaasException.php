<?php

namespace Asaas\Exception;

class AsaasException extends \Exception
{
    protected $errors;

    public function __construct(array $errors=[])
    {
        parent::__construct("Asaas Errors", 0, null);

        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrorByIndex(int $index=0)
    {
        if (empty($this->errors) || !isset($this->errors[$index])) {
            return null;
        }

        return $this->errors[$index];
    }

    public function addError($code, $description)
    {
        $this->errors[] = [
            'code' => $code,
            'description' => $description
        ];

        return $this;
    }
}