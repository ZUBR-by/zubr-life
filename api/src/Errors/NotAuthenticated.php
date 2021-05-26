<?php

namespace App\Errors;

use Exception;

class NotAuthenticated extends Exception implements ExpectedError
{
    const MESSAGE = 'Вы не авторизованы';

    public function __construct()
    {
        $this->message = self::MESSAGE;
    }
}
