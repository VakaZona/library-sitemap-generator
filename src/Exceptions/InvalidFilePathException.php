<?php

namespace vakazona\SitemapGenerator\Exceptions;

use Exception;
use Throwable;

class InvalidFilePathException extends Exception
{
    public function __construct($message = 'Failed to resolve file paths.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}