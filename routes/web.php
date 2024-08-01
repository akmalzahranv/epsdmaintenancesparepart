<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('products')->group(function () {
    Route::get('', [App\Http\Controllers\ProductController::class, 'products'])->name('products');
    Route::post('', [App\Http\Controllers\ProductController::class, 'product_save'])->name('products.save');
    Route::delete('', [App\Http\Controllers\ProductController::class, 'product_delete'])->name('products.delete')->middleware('adminRole');
    Route::get('wip', [App\Http\Controllers\ProductController::class, 'products_wip'])->name('products.wip');
    Route::post('wip', [App\Http\Controllers\ProductController::class, 'product_wip_save'])->name('products.wip.save');
    Route::post('wip/status', [App\Http\Controllers\ProductController::class, 'product_wip_status'])->name('products.wip.status');            
    Route::delete('wip', [App\Http\Controllers\ProductController::class, 'product_wip_delete'])->name('products.wip.delete');
    Route::post('wip/complete', [App\Http\Controllers\ProductController::class, 'product_wip_complete'])->name('products.wip.complete');
    Route::get('/check/{pcode}', [App\Http\Controllers\ProductController::class, 'product_check'])->name('products.check');
    Route::get('/stockOrder', [App\Http\Controllers\ProductController::class, 'product_stock_order'])->name('products.stock.order');
    Route::post('/stockOrder/status', [App\Http\Controllers\ProductController::class, 'product_stock_order_status'])->name('products.stock.order.status');    
    Route::post('/status/ordered', [App\Http\Controllers\ProductController::class, 'product_status_ordered'])->name('products.status.ordered');
    Route::post('/status/received', [App\Http\Controllers\ProductController::class, 'product_status_received'])->name('products.status.received');
    Route::delete('/stockOrder/status', [App\Http\Controllers\ProductController::class, 'product_stock_order_delete'])->name('products.stock.order.delete');    
    Route::post('/stockUpdate', [App\Http\Controllers\ProductController::class, 'product_stock'])->name('products.stock');
    Route::get('/stockHistory', [App\Http\Controllers\ProductController::class, 'product_stock_history'])->name('products.stock.history');
    Route::get('categories', [App\Http\Controllers\ProductController::class, 'categories'])->name('products.categories');
    Route::post('categories', [App\Http\Controllers\ProductController::class, 'categories_save'])->name('products.categories.save')->middleware('adminRole');
    Route::delete('categories', [App\Http\Controllers\ProductController::class, 'categories_delete'])->name('products.categories.delete')->middleware('adminRole');
    Route::get('line', [App\Http\Controllers\ProductController::class, 'line'])->name('products.line')->middleware('adminRole');
    Route::post('line', [App\Http\Controllers\ProductController::class, 'line_save'])->name('products.line.save')->middleware('adminRole');
    Route::delete('line', [App\Http\Controllers\ProductController::class, 'line_delete'])->name('products.line.delete')->middleware('adminRole');
    Route::get('machine', [App\Http\Controllers\ProductController::class, 'machine'])->name('products.machine')->middleware('adminRole');
    Route::post('machine', [App\Http\Controllers\ProductController::class, 'machine_save'])->name('products.machine.save')->middleware('adminRole');
    Route::delete('machine', [App\Http\Controllers\ProductController::class, 'machine_delete'])->name('products.machine.delete')->middleware('adminRole');
    Route::get('shelf', [App\Http\Controllers\ProductController::class, 'shelf'])->name('products.shelf');
    Route::get('/get-existing-shelf-ids', [App\Http\Controllers\ProductController::class, 'getExistingShelfIds']);
    Route::post('shelf', [App\Http\Controllers\ProductController::class, 'shelf_save'])->name('products.shelf.save')->middleware('adminRole');
    Route::delete('shelf', [App\Http\Controllers\ProductController::class, 'shelf_delete'])->name('products.shelf.delete')->middleware('adminRole');
    Route::get('barcode/{code}', [App\Http\Controllers\ProductController::class, 'generateBarcode'])->name('products.barcode');
    Route::get('search', [App\Http\Controllers\ProductController::class, 'searchProduct'])->name('products.search');
});

Route::prefix('users')->group(function () {
    Route::get('', [App\Http\Controllers\UserController::class, 'users'])->name('users')->middleware('adminRole');
    Route::delete('', [App\Http\Controllers\UserController::class, 'user_delete'])->name('users.delete')->middleware('adminRole');
    Route::post('', [App\Http\Controllers\UserController::class, 'user_save'])->name('users.save')->middleware('adminRole');
});

Route::prefix('account')->group(function () {
    Route::get('', [App\Http\Controllers\UserController::class, 'myaccount'])->name('myaccount');
    Route::post('profile', [App\Http\Controllers\UserController::class, 'myaccount_update'])->name('myaccount.update');
    Route::post('password', [App\Http\Controllers\UserController::class, 'myaccount_update_password'])->name('myaccount.updatePassword');
});