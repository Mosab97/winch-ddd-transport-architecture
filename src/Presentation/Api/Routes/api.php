<?php

use Illuminate\Support\Facades\Route;
use Src\Presentation\Api\Controllers\AssignOrderController;
use Src\Presentation\Api\Controllers\DriverOrdersController;

Route::post('/orders/{id}/assign', AssignOrderController::class);
Route::get('/drivers/{driver}/orders', DriverOrdersController::class);
