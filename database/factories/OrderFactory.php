<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Models\Entities\Order;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'pickup_latitude' => fake()->latitude(29.8, 30.2),
            'pickup_longitude' => fake()->longitude(31.0, 31.5),
            'status' => OrderStatusEnum::Pending,
            'driver_id' => null,
            'assigned_at' => null,
        ];
    }

    public function pending(): self
    {
        return $this->state([
            'status' => OrderStatusEnum::Pending,
            'driver_id' => null,
            'assigned_at' => null,
        ]);
    }

    public function assigned(?Driver $driver = null): self
    {
        return $this->state(fn () => [
            'status' => OrderStatusEnum::Assigned,
            'driver_id' => $driver?->id ?? Driver::factory()->create()->id,
            'assigned_at' => now(),
        ]);
    }
}
