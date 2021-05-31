<?php

namespace App\Auth;

use App\Errors\ExpectedError;
use Exception;

class Banned extends Exception implements ExpectedError
{
    protected $message = 'Вашему аккаунту был ограничен доступ';
}
