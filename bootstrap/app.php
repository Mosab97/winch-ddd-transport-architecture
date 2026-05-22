<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Src\Domain\Dispatch\Exceptions\DriverUnavailableException;
use Src\Domain\Dispatch\Exceptions\NoAvailableDriverException;
use Src\Domain\Orders\Exceptions\OrderAlreadyAssignedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../src/Presentation/Api/Routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (OrderAlreadyAssignedException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);
        });

        $exceptions->render(function (DriverUnavailableException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);
        });

        $exceptions->render(function (NoAvailableDriverException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        });
    })->create();
