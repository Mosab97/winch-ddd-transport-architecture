<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domain\Drivers\Enums\DriverStatusEnum;
use Src\Domain\Drivers\Models\Entities\Driver;

/**
 * @extends Factory<Driver>
 */
class DriverFactory extends Factory
{
    protected $model = Driver::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'latitude' => fake()->latitude(29.8, 30.2),
            'longitude' => fake()->longitude(31.0, 31.5),
            'status' => DriverStatusEnum::Available,
        ];
    }

    public function available(): self
    {
        return $this->state(['status' => DriverStatusEnum::Available]);
    }

    public function busy(): self
    {
        return $this->state(['status' => DriverStatusEnum::Busy]);
    }

    public function offline(): self
    {
        return $this->state(['status' => DriverStatusEnum::Offline]);
    }
}
