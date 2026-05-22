<?php

namespace Src\Presentation\Api\Controllers;

use Illuminate\Routing\Controller;
use Src\Domain\Dispatch\Actions\AssignOrderAction;
use Src\Presentation\Api\Requests\AssignOrderRequest;
use Src\Presentation\Api\Resources\OrderResource;

class AssignOrderController extends Controller
{
    public function __invoke(AssignOrderRequest $request, int $id, AssignOrderAction $action): OrderResource
    {
        return new OrderResource($action->execute($id));
    }
}
