<?php

namespace App\Errors;

use Exception;

class InvalidField extends Exception implements ExpectedError
{
    public function __construct(string $fieldName)
    {
        parent::__construct(sprintf('Некорректное значение в поле "%s"', $fieldName));
    }
}
