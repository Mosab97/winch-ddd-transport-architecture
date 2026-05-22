<?php

namespace Src\Domain\Dispatch\Exceptions;

use RuntimeException;

class NoAvailableDriverException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('No available driver was found for this order.');
    }
}
