<?php

namespace App;

use App\Errors\ExpectedError;
use Exception;

class EmptyField extends Exception implements ExpectedError
{
    public function __construct(string $fieldName)
    {
        parent::__construct(sprintf('Поле "%s" не должно быть пустым', $fieldName));
    }
}
