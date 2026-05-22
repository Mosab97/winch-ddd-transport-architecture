<?php

namespace Src\Presentation\Api\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Src\Domain\Dispatch\Exceptions\DriverUnavailableException;
use Src\Domain\Dispatch\Exceptions\NoAvailableDriverException;
use Src\Domain\Orders\Exceptions\OrderAlreadyAssignedException;
use Src\Presentation\Api\Responses\ApiResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionRenderer
{
    public static function render(Throwable $exception): ?JsonResponse
    {
        if ($exception instanceof OrderAlreadyAssignedException) {
            return ApiResponse::error($exception->getMessage(), 409);
        }

        if ($exception instanceof DriverUnavailableException) {
            return ApiResponse::error($exception->getMessage(), 409);
        }

        if ($exception instanceof NoAvailableDriverException) {
            return ApiResponse::error($exception->getMessage(), 422);
        }

        if ($exception instanceof ValidationException) {
            return ApiResponse::error('Validation failed', 422, $exception->errors());
        }

        if ($exception instanceof ModelNotFoundException) {
            return ApiResponse::error('Resource not found', 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            if ($exception->getPrevious() instanceof ModelNotFoundException) {
                return ApiResponse::error('Resource not found', 404);
            }

            return ApiResponse::error('Endpoint not found', 404);
        }

        if ($exception instanceof QueryException) {
            if (($exception->errorInfo[0] ?? null) === '22P02') {
                return ApiResponse::error('Invalid route parameter', 422);
            }

            return null;
        }

        return null;
    }
}
