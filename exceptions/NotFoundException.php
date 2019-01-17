<?php

namespace krivobokruslan\fayechat\exceptions;

use Throwable;

class NotFoundException extends \DomainException
{
    public function __construct($message = "Object not found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}