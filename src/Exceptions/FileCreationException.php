<?php

namespace vakazona\SitemapGenerator\Exceptions;

use Exception;
use Throwable;

class FileCreationException extends Exception
{
    public function __construct($message = 'Failed to create file', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}