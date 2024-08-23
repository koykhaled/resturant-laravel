<?php

namespace App\Exceptions\UserExceptions;

use Exception;

class UserNotFoundException extends Exception
{
    //
    protected $message = 'There is no account with this Email!';
    protected $code = 404;
}