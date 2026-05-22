<?php

namespace Src\Presentation\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Src\Domain\Drivers\Actions\GetDriversAction;
use Src\Presentation\Api\Requests\DriversIndexRequest;
use Src\Presentation\Api\Resources\DriverResource;
use Src\Presentation\Api\Responses\ApiResponse;

class DriversIndexController extends Controller
{
    public function __invoke(DriversIndexRequest $request, GetDriversAction $action): JsonResponse
    {
        return ApiResponse::paginated(
            'Drivers fetched successfully',
            DriverResource::collection(
                $action->execute($request->perPage())
            )
        );
    }
}
