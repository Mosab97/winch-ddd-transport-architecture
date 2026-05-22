<?php

use Illuminate\Support\Facades\Route;
use Src\Presentation\Api\Controllers\AssignOrderController;
use Src\Presentation\Api\Controllers\DriverOrdersController;
use Src\Presentation\Api\Controllers\DriversIndexController;
use Src\Presentation\Api\Controllers\OrdersIndexController;

Route::get('/orders', OrdersIndexController::class);
Route::post('/orders/{id}/assign', AssignOrderController::class)->whereNumber('id');
Route::get('/drivers', DriversIndexController::class);
Route::get('/drivers/{driver}/orders', DriverOrdersController::class)->whereNumber('driver');
