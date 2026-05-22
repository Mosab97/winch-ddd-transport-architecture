<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/orders', 'welcome');
Route::view('/drivers', 'welcome');
Route::get('/drivers/{driver}/orders', function () {
    return view('welcome');
})->whereNumber('driver');
