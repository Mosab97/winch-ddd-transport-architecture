<?php

namespace Src\Domain\Dispatch\Exceptions;

use RuntimeException;

class DriverUnavailableException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('The selected driver is no longer available.');
    }
}
