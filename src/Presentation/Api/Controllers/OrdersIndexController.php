<?php

namespace Src\Presentation\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Src\Domain\Orders\Actions\GetOrdersAction;
use Src\Presentation\Api\Requests\OrdersIndexRequest;
use Src\Presentation\Api\Resources\OrderResource;
use Src\Presentation\Api\Responses\ApiResponse;

class OrdersIndexController extends Controller
{
    public function __invoke(OrdersIndexRequest $request, GetOrdersAction $action): JsonResponse
    {
        return ApiResponse::paginated(
            'Orders fetched successfully',
            OrderResource::collection(
                $action->execute($request->status(), $request->perPage())
            )
        );
    }
}
