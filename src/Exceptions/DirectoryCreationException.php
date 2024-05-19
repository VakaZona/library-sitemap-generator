<?php

namespace vakazona\SitemapGenerator\Exceptions;

use Exception;
use Throwable;

class DirectoryCreationException extends Exception
{
    public function __construct($message = 'Failed to create directory', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
