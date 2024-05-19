<?php

namespace vakazona\SitemapGenerator\Exceptions;

use Exception;
use Throwable;

class InvalidFileExtensionException extends Exception
{
    public function __construct($message = 'Invalid file extension', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}