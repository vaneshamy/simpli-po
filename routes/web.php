<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/midtrans-callback', [OrderController::class, 'callback']);