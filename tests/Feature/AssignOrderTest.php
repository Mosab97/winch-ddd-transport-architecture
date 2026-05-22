<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domain\Drivers\Enums\DriverStatusEnum;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Models\Entities\Order;
use Tests\TestCase;

class AssignOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigns_pending_order_to_closest_available_driver(): void
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
            'latitude' => 30.0450,
            'longitude' => 31.2360,
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/assign");

        $response->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.status', OrderStatusEnum::Assigned->value)
            ->assertJsonPath('data.driver_id', $nearDriver->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'driver_id' => $nearDriver->id,
            'status' => OrderStatusEnum::Assigned->value,
        ]);

        $this->assertDatabaseHas('drivers', [
            'id' => $nearDriver->id,
            'status' => DriverStatusEnum::Busy->value,
        ]);

        $this->assertDatabaseHas('drivers', [
            'id' => $farDriver->id,
            'status' => DriverStatusEnum::Available->value,
        ]);
    }

    public function test_assigned_order_cannot_be_assigned_again(): void
    {
        $driver = Driver::factory()->busy()->create();
        $order = Order::factory()->assigned($driver)->create();

        $response = $this->postJson("/api/orders/{$order->id}/assign");

        $response->assertStatus(409)
            ->assertJsonPath('message', 'This order has already been assigned.');
    }

    public function test_busy_and_offline_drivers_are_ignored(): void
    {
        $order = Order::factory()->create([
            'pickup_latitude' => 30.0444,
            'pickup_longitude' => 31.2357,
        ]);

        Driver::factory()->busy()->create([
            'latitude' => 30.0445,
            'longitude' => 31.2358,
        ]);

        Driver::factory()->offline()->create([
            'latitude' => 30.0446,
            'longitude' => 31.2359,
        ]);

        $availableDriver = Driver::factory()->available()->create([
            'latitude' => 30.1000,
            'longitude' => 31.3000,
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/assign");

        $response->assertOk()
            ->assertJsonPath('data.driver_id', $availableDriver->id);
    }

    public function test_driver_with_active_assigned_order_is_ignored(): void
    {
        $order = Order::factory()->create([
            'pickup_latitude' => 30.0444,
            'pickup_longitude' => 31.2357,
        ]);

        $activeDriver = Driver::factory()->available()->create([
            'latitude' => 30.0445,
            'longitude' => 31.2358,
        ]);

        Order::factory()->assigned($activeDriver)->create();

        $availableDriver = Driver::factory()->available()->create([
            'latitude' => 30.1200,
            'longitude' => 31.3200,
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/assign");

        $response->assertOk()
            ->assertJsonPath('data.driver_id', $availableDriver->id);
    }

    public function test_returns_clean_error_when_no_driver_is_available(): void
    {
        $order = Order::factory()->create();

        Driver::factory()->busy()->create();
        Driver::factory()->offline()->create();

        $response = $this->postJson("/api/orders/{$order->id}/assign");

        $response->assertStatus(422)
            ->assertJsonPath('message', 'No available driver was found for this order.');
    }

    public function test_repeated_assignment_attempts_do_not_create_duplicate_assignment(): void
    {
        $order = Order::factory()->create();
        Driver::factory()->available()->create();
        Driver::factory()->available()->create();

        $this->postJson("/api/orders/{$order->id}/assign")->assertOk();
        $this->postJson("/api/orders/{$order->id}/assign")->assertStatus(409);

        $this->assertSame(1, Order::query()->whereKey($order->id)->whereNotNull('driver_id')->count());
        $this->assertSame(1, Driver::query()->where('status', DriverStatusEnum::Busy)->count());
    }
}
