<?php

namespace Src\Domain\Dispatch\Services;

use Illuminate\Support\Collection;
use Src\Domain\Dispatch\Contracts\DistanceCalculatorContract;
use Src\Domain\Dispatch\Contracts\FindBestAvailableDriverContract;
use Src\Domain\Drivers\Enums\DriverStatusEnum;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Models\Entities\Order;

class FindBestAvailableDriverService implements FindBestAvailableDriverContract
{
    public function __construct(
        private readonly DistanceCalculatorContract $distanceCalculator,
    ) {}

    public function forOrder(Order $order): ?Driver
    {
        return $this->candidatesForOrder($order)->first();
    }

    public function candidatesForOrder(Order $order): Collection
    {
        return Driver::query()
            ->where('status', DriverStatusEnum::Available)
            ->whereDoesntHave('activeAssignedOrder')
            ->get()
            ->sortBy(fn (Driver $driver): float => $this->distanceCalculator->haversineKilometers(
                $order->pickup_latitude,
                $order->pickup_longitude,
                $driver->latitude,
                $driver->longitude,
            ))
            ->values();
    }
}
