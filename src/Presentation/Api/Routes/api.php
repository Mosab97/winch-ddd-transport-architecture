<?php

use Illuminate\Support\Facades\Route;
use Src\Presentation\Api\Controllers\AssignOrderController;
use Src\Presentation\Api\Controllers\DriverOrdersController;

Route::post('/orders/{id}/assign', AssignOrderController::class)->whereNumber('id');
Route::get('/drivers/{driver}/orders', DriverOrdersController::class)->whereNumber('driver');
