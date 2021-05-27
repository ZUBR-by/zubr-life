<?php

namespace App\Auth;

use App\Errors\ExpectedError;
use Exception;

class NotInGroups extends Exception implements ExpectedError
{
    protected $message = 'Нужно быть участников районных чатов указанных на сайте';
}
