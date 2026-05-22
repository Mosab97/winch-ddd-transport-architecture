<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domain\Dispatch\Services\DistanceCalculatorService;
use Src\Domain\Dispatch\Services\FindBestAvailableDriverService;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Models\Entities\Order;
use Tests\TestCase;

class FindBestAvailableDriverServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_distance_sorting_selects_nearest_eligible_driver(): void
    {
        $order = Order::factory()->create([
            'pickup_latitude' => 30.0444,
            'pickup_longitude' => 31.2357,
        ]);

        $farDriver = Driver::factory()->available()->create([
            'latitude' => 30.2000,
            'longitude' => 31.5000,
        ]);

        $nearDriver = Driver::factory()->available()->create([
            'latitude' => 30.0446,
            'longitude' => 31.2360,
        ]);

        $service = new FindBestAvailableDriverService(new DistanceCalculatorService);

        $this->assertTrue($nearDriver->is($service->forOrder($order)));
        $this->assertFalse($farDriver->is($service->forOrder($order)));
    }
}
