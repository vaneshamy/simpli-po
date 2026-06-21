<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Models\Product;

Route::get('/', function () {
   $products = Product::all();
   return view('welcome', compact('products'));
});

Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/midtrans-callback', [OrderController::class, 'callback']);