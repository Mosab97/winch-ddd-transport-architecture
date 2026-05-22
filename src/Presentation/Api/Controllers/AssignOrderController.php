<?php

namespace Src\Presentation\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Src\Domain\Dispatch\Actions\AssignOrderAction;
use Src\Presentation\Api\Requests\AssignOrderRequest;
use Src\Presentation\Api\Resources\OrderResource;
use Src\Presentation\Api\Responses\ApiResponse;

class AssignOrderController extends Controller
{
    public function __invoke(AssignOrderRequest $request, int $id, AssignOrderAction $action): JsonResponse
    {
        return ApiResponse::success(
            'Order assigned successfully',
            new OrderResource($action->execute($id))
        );
    }
}
