<?php

namespace Src\Domain\Drivers\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;

class GetDriverOrdersAction
{
    public function execute(Driver $driver, ?OrderStatusEnum $status, int $perPage = 15): LengthAwarePaginator
    {
        return $driver->orders()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }
}
