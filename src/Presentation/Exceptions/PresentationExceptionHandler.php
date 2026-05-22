<?php

namespace Src\Presentation\Exceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Src\Presentation\Api\Exceptions\ApiExceptionRenderer;
use Src\Presentation\Api\Responses\ApiResponse;
use Throwable;

class PresentationExceptionHandler
{
    public static function register(Exceptions $exceptions): void
    {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if (! self::isApiRequest($request)) {
                return null;
            }

            return ApiExceptionRenderer::render($exception)
                ?? ApiResponse::error(self::fallbackMessage($exception), 500);
        });
    }

    private static function isApiRequest(Request $request): bool
    {
        return $request->is('api/*');
    }

    private static function fallbackMessage(Throwable $exception): string
    {
        if (app()->isProduction()) {
            return 'Something went wrong';
        }

        return $exception->getMessage() ?: 'Something went wrong';
    }
}
