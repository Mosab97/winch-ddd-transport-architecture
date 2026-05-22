<?php

namespace Src\Domain\Orders\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Models\Entities\Order;

class GetOrdersAction
{
    public function execute(?OrderStatusEnum $status, int $perPage = 15): LengthAwarePaginator
    {
        return Order::query()
            ->with('driver')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }
}
