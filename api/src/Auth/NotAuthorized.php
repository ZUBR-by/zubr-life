<?php

namespace App\Auth;

use App\Errors\ExpectedError;
use Exception;

class NotAuthorized extends Exception implements ExpectedError
{
    protected $message = 'Вы не авторизованы';
}
