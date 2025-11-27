<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\DriverApiController;
use App\Http\Controllers\API\CustomerApiController;
use App\Http\Controllers\API\SyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/driver/login', [AuthController::class, 'login']);
Route::get('/driver/cars', [DriverApiController::class, 'cars_list']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/driver/user', [DriverApiController::class, 'driver_info']);

    Route::post('/driver/logout', [AuthController::class, 'logout']);

    Route::get('/driver/orders_list', [DriverApiController::class, 'orders_list']);
    Route::post('/driver/customer_items_list', [DriverApiController::class, 'customer_items_list']);
    Route::get('/driver/laundry_packages', [DriverApiController::class, 'laundry_packages']);
    Route::post('/driver/pick-up', [DriverApiController::class, 'driver_pickup']);


    Route::post('/driver/on-way', [DriverApiController::class, 'driver_on_way']);
    Route::post('/driver/redo_items', [DriverApiController::class, 'redo_items']);

    Route::post('/driver/transfer_request', [DriverApiController::class, 'transfer_request']);
    Route::get('/driver/transfer_history', [DriverApiController::class, 'transfer_history']);
    Route::get('/driver/customers_list', [DriverApiController::class, 'customers_list']);
    Route::post('/driver/previous_orders', [DriverApiController::class, 'previous_orders']);

    Route::get('/driver/delivery_details', [DriverApiController::class, 'deliveryDetails']);

    Route::get('/driver/dashboard_data', [DriverApiController::class, 'DashbaordData']);

    Route::post('/driver/delivery_complete', [DriverApiController::class, 'deliveryComplete']);

    Route::get('/driver/printer', [DriverApiController::class, 'printer']);

    Route::post('/driver/invoice', [DriverApiController::class, 'invoice']);
});

// Customer-related routes
Route::post('/customer/register', [AuthController::class, 'register']);
Route::post('/customer/login', [AuthController::class, 'customerLogin']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/customer/customers_logout', [AuthController::class, 'customers_logout']);
    Route::post('/customer/order_create', [CustomerApiController::class, 'order_create']);
    Route::get('/customer/order_list', [CustomerApiController::class, 'orderList']); // New route for order list
    Route::get('/customer/completed_orders', [CustomerApiController::class, 'completedOrders']); // New route for completed orders
    Route::get('/customer/completed_order', [CustomerApiController::class, 'completedOrderDetail']);
    Route::get('/customer/dashboard', [CustomerApiController::class, 'dashboard']);
    Route::get('/customer/profile', [CustomerApiController::class, 'userProfile']); // New route for user profile

});
Route::get('/customer/default_pricing', [CustomerApiController::class, 'setDefaultPricing']);

//sync
Route::get('/sync/send-new-order', [SyncController::class, 'sendNewOrder']);
