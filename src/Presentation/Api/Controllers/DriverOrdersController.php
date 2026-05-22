<?php

namespace Src\Presentation\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Src\Domain\Drivers\Actions\GetDriverOrdersAction;
use Src\Domain\Drivers\Models\Entities\Driver;
use Src\Presentation\Api\Requests\DriverOrdersRequest;
use Src\Presentation\Api\Resources\OrderResource;
use Src\Presentation\Api\Responses\ApiResponse;

class DriverOrdersController extends Controller
{
    public function __invoke(
        DriverOrdersRequest $request,
        Driver $driver,
        GetDriverOrdersAction $action,
    ): JsonResponse {
        return ApiResponse::paginated(
            'Driver orders fetched successfully',
            OrderResource::collection(
                $action->execute($driver, $request->status(), $request->perPage())
            )
        );
    }
}
