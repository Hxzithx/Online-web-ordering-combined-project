<?php

use App\Http\Controllers\AddOnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemPriceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemAdOnController;
use App\Http\Controllers\TableMergeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageItemController;
use App\Http\Controllers\PortionController;
use App\Http\Controllers\TableController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('/auth/google', [GoogleController::class, 'handleGoogleLogin']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
    //orders
    Route::get('/orders', [OrderController::class, 'index']);   // list own orders
    Route::post('/order', [OrderController::class, 'store']);   // place new order + table booking
    Route::get('/orders/{order}', [OrderController::class, 'show']);    // view single order
    Route::get('/orders/{order}/status', [OrderController::class, 'status']); // track status
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']); // cancel order
    Route::patch('/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment']);  // confirm card payment
    Route::get('orders/{order}/items', [OrderItemController::class, 'index']); //get all order items
    //addon
    Route::get('/add-ons', [AddOnController::class, 'index']); // basic add ons


    //portion
    Route::prefix('portions')->group(function () {
        Route::get('/', [PortionController::class, 'index']);
    });

    //order item add ons
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/order-items/{order_item_id}/add-ons', [OrderItemAdOnController::class, 'index']);
        Route::post('order-items/{order_item_id}/add-ons', [OrderItemAdOnController::class, 'store']);

        //tables with merge
        Route::prefix('tables')->group(function () {
            Route::get('/', [TableController::class, 'index']); //get all tables
            Route::get('/available', [TableController::class, 'available']);
            Route::put('/{table_id}/update', [TableController::class], 'update'); //update the table
            Route::delete('/{table_id}/delete', [TableController::class], 'destroy'); //delete the table
            Route::get('/merge', [TableMergeController::class, 'index']); //get all the merges
            Route::post('/merge', [TableMergeController::class, 'store']); //create table merge
            Route::get('/available-merges', [TableController::class, 'available']); //browse available tables
            Route::get('/merges', [TableMergeController::class, 'active']); //view active merges
            Route::post('/merge', [TableMergeController::class, 'merge']); //request a merge table
        });

        //customers
        Route::prefix('customer')->group(function () {
            Route::get('/profile', [CustomerController::class, 'profile']);
            Route::get('/', [CustomerController::class, 'index']);
            Route::post('/', [CustomerController::class, 'store']);
            Route::get('/{customer_id}', [CustomerController::class, 'show']);
            Route::match(['post', 'put'], '/profile', [CustomerController::class, 'update']);
            Route::delete('/{customer_id}/delete', [CustomerController::class, 'destroy']);
        });

        //items
        Route::prefix('menu')->group(function () {
            Route::get('/', [ItemController::class, 'index']); //get all items
            Route::post('/', [ItemController::class, 'store']); //create new item

            Route::get('/item-prices', [ItemPriceController::class, 'index']); //get all item prices
            Route::post('/item-prices', [ItemPriceController::class, 'store']); //cerate item price
            Route::get('/item-prices/get-price', [ItemPriceController::class, 'getPrice']); //get item price
            Route::get('/item-prices/order-type/{orderTypeId}', [ItemPriceController::class, 'getPricesByOrderType']);
            Route::get('/item-prices/item/{itemId}', [ItemPriceController::class, 'getItemPrices']);
            Route::get('/item-prices/{itemPrices}', [ItemPriceController::class, 'show']);
            Route::put('/item-prices/{itemPrices}', [ItemPriceController::class, 'update']);
            Route::delete('/item-prices/{itemPrices}', [ItemPriceController::class, 'destroy']);

            Route::get('/{item}', [ItemController::class, 'show']);
            Route::put('/{item}/update', [ItemController::class, 'update']);
            Route::delete('/{item}/delete', [ItemController::class, 'delete']);
        });

        Route::prefix('package')->group(function () {
            Route::get('/', [PackageController::class, 'index']); //get all packages  
            Route::post('/', [PackageController::class, 'store']);
            Route::get('/{package_id}', [PackageController::class, 'show']);
            Route::put('/{package_id}', [PackageController::class, 'update']);
            Route::delete('/{package_id}', [PackageController::class, 'destroy']);
        });

        Route::prefix('packageItem')->group(function () {
            Route::get('/', [PackageItemController::class, 'index']);
            Route::post('/', [PackageItemController::class, 'show']);
            Route::get('/{packageItem_id}', [PackageItemController::class, 'show']);
            Route::put('/{packageItem_id}', [PackageItemController::class, 'update']);
            Route::delete('/{packageItem_id}', [PackageItemController::class, 'delete']);
        });
    });
    Route::get('/users', [UserController::class, 'index']);
});
