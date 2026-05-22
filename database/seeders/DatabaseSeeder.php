<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Src\Domain\Drivers\Enums\DriverStatusEnum;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Models\Entities\Order;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Driver::factory()
            ->count(8)
            ->sequence(
                ['name' => 'Cairo Driver 1', 'latitude' => 30.0444, 'longitude' => 31.2357],
                ['name' => 'Giza Driver 1', 'latitude' => 30.0131, 'longitude' => 31.2089],
                ['name' => 'Nasr City Driver 1', 'latitude' => 30.0561, 'longitude' => 31.3300],
                ['name' => 'Maadi Driver 1', 'latitude' => 29.9602, 'longitude' => 31.2569],
            )
            ->create();

        Driver::factory()->busy()->create([
            'name' => 'Busy Driver',
            'latitude' => 30.0500,
            'longitude' => 31.2400,
        ]);

        Driver::factory()->offline()->create([
            'name' => 'Offline Driver',
            'latitude' => 30.0600,
            'longitude' => 31.2500,
        ]);

        Order::factory()->count(12)->pending()->create();

        $busyDriver = Driver::query()->where('status', DriverStatusEnum::Busy)->first();

        Order::factory()->create([
            'pickup_latitude' => 30.0450,
            'pickup_longitude' => 31.2360,
            'status' => OrderStatusEnum::Assigned,
            'driver_id' => $busyDriver?->id,
            'assigned_at' => now(),
        ]);
    }
}
