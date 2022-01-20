<?php

namespace App\Containers\Core\Authentication\Exceptions;

use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class LoginFailedException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;
    protected $message = 'An Exception happened during the Login Process.';
}
