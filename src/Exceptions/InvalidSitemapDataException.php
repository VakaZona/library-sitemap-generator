<?php

namespace vakazona\SitemapGenerator\Exceptions;

use Exception;
use Throwable;

class InvalidSitemapDataException extends Exception
{
    public function __construct($message = 'Invalid file type', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
