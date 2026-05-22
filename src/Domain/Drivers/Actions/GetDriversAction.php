<?php

namespace Src\Domain\Drivers\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Domain\Drivers\Models\Entities\Driver;

class GetDriversAction
{
    public function execute(int $perPage = 15): LengthAwarePaginator
    {
        return Driver::query()
            ->latest()
            ->paginate($perPage);
    }
}
