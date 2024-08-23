<?php

namespace App\Exceptions\UserExceptions;

use Exception;

class UserNotActiveException extends Exception
{
    //
    protected $message = 'Please activate your account before login!';
    protected $code = 403;
}