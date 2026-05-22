<?php

namespace Src\Domain\Dispatch\Services;

use Illuminate\Support\Carbon;
use Src\Domain\Dispatch\Contracts\AssignOrderToDriverContract;
use Src\Domain\Dispatch\Exceptions\DriverUnavailableException;
use Src\Domain\Drivers\Enums\DriverStatusEnum;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Exceptions\OrderAlreadyAssignedException;
use Src\Domain\Orders\Models\Entities\Order;

class AssignOrderToDriverService implements AssignOrderToDriverContract
{
    public function assign(Order $order, Driver $driver): Order
    {
        if (! $order->isPending()) {
            throw new OrderAlreadyAssignedException;
        }

        if ($driver->status !== DriverStatusEnum::Available || $driver->activeAssignedOrder()->exists()) {
            throw new DriverUnavailableException;
        }

        $order->forceFill([
            'driver_id' => $driver->id,
            'status' => OrderStatusEnum::Assigned,
            'assigned_at' => Carbon::now(),
        ])->save();

        $driver->forceFill([
            'status' => DriverStatusEnum::Busy,
        ])->save();

        return $order->refresh()->load('driver');
    }
}
