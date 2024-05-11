<?php

namespace src\Exceptions;

use Exception;
use Throwable;


class InvalidFileTypeExceptions extends Exception
{
    public function __construct($message = 'Invalid file type', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
