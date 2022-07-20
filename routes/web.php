<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/testing', [PublicController::class, 'testing'])->name('public.testing');
Route::get('/menu/{slug}', [PublicController::class, 'show'])->name('public.show');
Route::post('/auth/destroy', [AuthController::class, 'destroy'])->name('auth.destroy');

Route::group(['middleware' => ['auth.check']], function () {
    Route::prefix('auth/')->namespace('Cart')->group(function () {
        Route::get('', [AuthController::class, 'index'])->name('auth.index');
        Route::post('', [AuthController::class, 'store'])->name('auth.store');
    });
});

Route::group(['middleware' => ['login.check']], function () {
    Route::prefix('order/')->namespace('Order')->group(function () {
        Route::get('', [OrderController::class, 'index'])->name('order.index');
        Route::post('complete', [OrderController::class, 'complete'])->name('order.complete');
    });

    Route::prefix('cart/')->namespace('Cart')->group(function () {
        Route::get('', [CartController::class, 'index'])->name('cart.index');
        Route::post('', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('add-to-cart', [CartController::class, 'store'])->name('cart.add-to-cart');
    });
});

