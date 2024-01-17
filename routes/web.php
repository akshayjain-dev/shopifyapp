<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\DropshipOrdersController;
use App\Http\Controllers\UpdateOrderController;
use App\Http\Controllers\AccountController;
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

Route::get('/', function () {
    return view('welcome');
})->middleware(['verify.shopify'])->name('home');

Route::get('/login', function () {
    return view('auth.login');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
    Route::patch('/configuration', [ConfigurationController::class, 'update'])->name('configuration.update');
    Route::resource('orders', 'App\Http\Controllers\OrdersController');
    //Route::post('/orders/search/', 'App\Http\Controllers\OrdersController@index')->name('search');
    Route::get('/ssltd/dropship-dashboard', 'App\Http\Controllers\DropshipDashboardController@index');
    Route::patch('/ssltd/dropship-orders', [DropshipOrdersController::class, 'performAction'])->name('dropshiporder.performAction');
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
   
});
Route::post('/update-order-status', [UpdateOrderController::class, 'updateOrderStatus']);

