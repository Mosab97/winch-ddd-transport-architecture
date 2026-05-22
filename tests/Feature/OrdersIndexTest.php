<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Models\Entities\Order;
use Tests\TestCase;

class OrdersIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_endpoint_paginates_results(): void
    {
        $driver = Driver::factory()->create();
        Order::factory()->count(3)->assigned($driver)->create();

        $response = $this->getJson('/api/orders?per_page=2');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Orders fetched successfully')
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonStructure(['links'])
            ->assertJsonPath('code', 200);
    }

    public function test_orders_endpoint_filters_by_status(): void
    {
        Order::factory()->create(['status' => OrderStatusEnum::Pending]);
        Order::factory()->create(['status' => OrderStatusEnum::Completed]);

        $response = $this->getJson('/api/orders?status=completed');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Orders fetched successfully')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', OrderStatusEnum::Completed->value)
            ->assertJsonPath('code', 200);
    }

    public function test_orders_endpoint_rejects_invalid_status(): void
    {
        $response = $this->getJson('/api/orders?status=unknown');

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Validation failed')
            ->assertJsonPath('data', [])
            ->assertJsonPath('code', 422)
            ->assertJsonValidationErrors('status');
    }
}
