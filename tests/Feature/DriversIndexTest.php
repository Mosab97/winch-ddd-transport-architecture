<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domain\Drivers\Models\Entities\Driver;
use Tests\TestCase;

class DriversIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_drivers_endpoint_paginates_results(): void
    {
        Driver::factory()->count(3)->create();

        $response = $this->getJson('/api/drivers?per_page=2');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Drivers fetched successfully')
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonStructure(['links'])
            ->assertJsonPath('code', 200);
    }

    public function test_drivers_endpoint_rejects_invalid_per_page(): void
    {
        $response = $this->getJson('/api/drivers?per_page=101');

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Validation failed')
            ->assertJsonPath('data', [])
            ->assertJsonPath('code', 422)
            ->assertJsonValidationErrors('per_page');
    }

    public function test_drivers_endpoint_rejects_invalid_page(): void
    {
        $response = $this->getJson('/api/drivers?page=0');

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Validation failed')
            ->assertJsonPath('data', [])
            ->assertJsonPath('code', 422)
            ->assertJsonValidationErrors('page');
    }
}
