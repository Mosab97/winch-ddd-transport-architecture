<?php

namespace Src\Domain\Orders\Models\Entities;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Domain\Orders\Enums\OrderStatusEnum;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'pickup_latitude',
        'pickup_longitude',
        'status',
        'driver_id',
        'assigned_at',
    ];

    protected function casts(): array
    {
        return [
            'pickup_latitude' => 'float',
            'pickup_longitude' => 'float',
            'status' => OrderStatusEnum::class,
            'assigned_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatusEnum::Pending;
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
