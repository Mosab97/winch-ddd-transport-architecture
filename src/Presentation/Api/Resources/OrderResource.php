<?php

namespace Src\Presentation\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pickup_latitude' => $this->pickup_latitude,
            'pickup_longitude' => $this->pickup_longitude,
            'status' => $this->status->value,
            'driver_id' => $this->driver_id,
            'assigned_at' => $this->assigned_at?->toISOString(),
            'driver' => new DriverResource($this->whenLoaded('driver')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
