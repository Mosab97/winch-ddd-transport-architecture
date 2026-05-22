<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Models\Entities\Order;
use Tests\TestCase;

class DriverOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_driver_orders_endpoint_paginates_results(): void
    {
        $driver = Driver::factory()->create();
        Order::factory()->count(3)->assigned($driver)->create();

        $response = $this->getJson("/api/drivers/{$driver->id}/orders?per_page=2");

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Driver orders fetched successfully')
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonStructure(['links'])
            ->assertJsonPath('code', 200);
    }

    public function test_driver_orders_endpoint_filters_by_status(): void
    {
        $driver = Driver::factory()->create();

        Order::factory()->assigned($driver)->create();
        Order::factory()->create([
            'driver_id' => $driver->id,
            'status' => OrderStatusEnum::Completed,
            'assigned_at' => now()->subDay(),
        ]);

        $response = $this->getJson("/api/drivers/{$driver->id}/orders?status=completed");

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Driver orders fetched successfully')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', OrderStatusEnum::Completed->value)
            ->assertJsonPath('code', 200);
    }

    public function test_invalid_status_filter_returns_validation_error(): void
    {
        $driver = Driver::factory()->create();

        $response = $this->getJson("/api/drivers/{$driver->id}/orders?status=unknown");

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Validation failed')
            ->assertJsonPath('data', [])
            ->assertJsonPath('code', 422)
            ->assertJsonValidationErrors('status');
    }

    public function test_literal_driver_id_placeholder_returns_structured_not_found_response(): void
    {
        $response = $this->getJson('/api/drivers/{id}/orders');

        $response->assertStatus(404)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Endpoint not found')
            ->assertJsonPath('data', [])
            ->assertJsonPath('code', 404);
    }

    public function test_missing_driver_returns_structured_not_found_response(): void
    {
        $response = $this->getJson('/api/drivers/999/orders');

        $response->assertStatus(404)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Resource not found')
            ->assertJsonPath('data', [])
            ->assertJsonPath('code', 404);
    }

    public function test_missing_endpoint_returns_structured_not_found_response(): void
    {
        $response = $this->getJson('/api/not-real');

        $response->assertStatus(404)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Endpoint not found')
            ->assertJsonPath('data', [])
            ->assertJsonPath('code', 404);
    }
}
