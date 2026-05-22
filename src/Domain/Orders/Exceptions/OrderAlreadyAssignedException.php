<?php

namespace Src\Domain\Orders\Exceptions;

use RuntimeException;

class OrderAlreadyAssignedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('This order has already been assigned.');
    }
}
