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
                ['name' => 'Ahmed Hassan', 'latitude' => 30.0444, 'longitude' => 31.2357],
                ['name' => 'Mohamed Ali', 'latitude' => 30.0131, 'longitude' => 31.2089],
                ['name' => 'Omar Farouk', 'latitude' => 30.0561, 'longitude' => 31.3300],
                ['name' => 'Youssef Ibrahim', 'latitude' => 29.9602, 'longitude' => 31.2569],
                ['name' => 'Karim Mahmoud', 'latitude' => 30.0900, 'longitude' => 31.3200],
                ['name' => 'Tarek Nabil', 'latitude' => 30.0626, 'longitude' => 31.2197],
                ['name' => 'Hossam Adel', 'latitude' => 30.0380, 'longitude' => 31.2100],
                ['name' => 'Mahmoud Saeed', 'latitude' => 30.0680, 'longitude' => 31.2450],
            )
            ->create();

        Driver::factory()->busy()->create([
            'name' => 'Ibrahim Yassin',
            'latitude' => 30.0500,
            'longitude' => 31.2400,
        ]);

        Driver::factory()->offline()->create([
            'name' => 'Khalid Mansour',
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
