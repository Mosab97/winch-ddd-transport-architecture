<?php

namespace Src\Domain\Dispatch\Contracts;

use Illuminate\Support\Collection;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Models\Entities\Order;

interface FindBestAvailableDriverContract
{
    public function forOrder(Order $order): ?Driver;

    /**
     * @return Collection<int, Driver>
     */
    public function candidatesForOrder(Order $order): Collection;
}
