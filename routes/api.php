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

    
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::post('add-product/{id}', [ProductController::class, 'addProductToOrder']);
    Route::get('/total', [ProductController::class, 'getTotalSoldProductCounts']);
    Route::get('/product-list', [ProductController::class, 'productList']);
    
    Route::post('/order', [OrderController::class, 'placeOrder']);
    Route::get('/orders', [OrderController::class, 'getAllOrders']);
    Route::get('/cart', [OrderController::class, 'cart']);
    Route::get('/user-purchases', [OrderController::class, 'userPurchases']);
    Route::get('/orders-user', [OrderController::class, 'getOrdersByUser']);
    Route::delete('/delete-order/{id}', [OrderController::class, 'deleteOrder']);
    Route::post('/update-cart/{order_id}/{product_id}', [OrderController::class, 'updateProductCart']);
    Route::delete('/delete-product-cart/{order_id}/{product_id}', [OrderController::class, 'deleteProductCart']);



});
