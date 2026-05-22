<?php

namespace Src\Domain\Dispatch\Contracts;

use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Models\Entities\Order;

interface AssignOrderToDriverContract
{
    public function assign(Order $order, Driver $driver): Order;
}
