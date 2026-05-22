<?php

namespace Src\Domain\Dispatch\Actions;

use Illuminate\Support\Facades\DB;
use Src\Domain\Dispatch\Contracts\AssignOrderToDriverContract;
use Src\Domain\Dispatch\Contracts\FindBestAvailableDriverContract;
use Src\Domain\Dispatch\Exceptions\DriverUnavailableException;
use Src\Domain\Dispatch\Exceptions\NoAvailableDriverException;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Exceptions\OrderAlreadyAssignedException;
use Src\Domain\Orders\Models\Entities\Order;

class AssignOrderAction
{
    public function __construct(
        private readonly FindBestAvailableDriverContract $bestAvailableDriver,
        private readonly AssignOrderToDriverContract $assignOrderToDriver,
    ) {}

    public function execute(int $orderId): Order
    {
        return DB::transaction(function () use ($orderId): Order {
            $order = Order::query()->lockForUpdate()->findOrFail($orderId);

            if (! $order->isPending()) {
                throw new OrderAlreadyAssignedException();
            }

            foreach ($this->bestAvailableDriver->candidatesForOrder($order) as $candidate) {
                $driver = Driver::query()->lockForUpdate()->find($candidate->id);

                if ($driver === null) {
                    continue;
                }

                try {
                    return $this->assignOrderToDriver->assign($order, $driver);
                } catch (DriverUnavailableException) {
                    continue;
                }
            }

            throw new NoAvailableDriverException();
        });
    }
}
