<?php

namespace App\Exceptions\UserExceptions;

use Exception;

class InvalidCredintialsException extends Exception
{
    protected $message = 'Invalid Credentials';
    protected $code = 401;
}