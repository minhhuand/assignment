<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('order/', [ProductController::class, 'placeOrder']);

    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);

    Route::get('add-product/{id}', [ProductController::class, 'addProductToOrder']);
    Route::get('/orders', [ProductController::class, 'getAllOrders']);
    Route::get('/total', [ProductController::class, 'getTotalSoldProductCounts']);

    Route::get('/cart', [OrderController::class, 'cart']);
});
// Route::get('/top-products', [ProductController::class, 'topProducts']);
// Route::get('/products', [ProductController::class, 'show']);
//Route::get('/orders', [ProductController::class, 'getAllOrders']);
