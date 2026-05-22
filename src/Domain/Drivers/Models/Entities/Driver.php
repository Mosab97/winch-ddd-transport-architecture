<?php

namespace Src\Domain\Drivers\Models\Entities;

use Database\Factories\DriverFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Src\Domain\Drivers\Enums\DriverStatusEnum;
use Src\Domain\Orders\Enums\OrderStatusEnum;
use Src\Domain\Orders\Models\Entities\Order;

class Driver extends Model
{
    /** @use HasFactory<DriverFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'status' => DriverStatusEnum::class,
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeAssignedOrder(): HasOne
    {
        return $this->hasOne(Order::class)->where('status', OrderStatusEnum::Assigned);
    }

    protected static function newFactory(): DriverFactory
    {
        return DriverFactory::new();
    }
}
