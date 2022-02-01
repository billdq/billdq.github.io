<?php

use Illuminate\Support\Facades\Route;

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
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/create_order', 'OrderController@createOrder')->name('createOrder');
Route::get('/orders', 'OrderController@index');
Route::get('/completed_orders', 'OrderController@completed');
Route::post('/orders', 'OrderController@store');
Route::get('/orders/{id}', 'OrderController@show')->name('orders');
Route::post('/orders/{id}', 'OrderController@update');
Route::delete('/orders/{id}', 'OrderController@destroy');
Route::get('/order_cat/{id}/qr_codes', 'OrderCatController@qrCodes');
Route::get('/order_cat/{id}/qr_codes/print', 'OrderCatController@printQrCodes');
Route::delete('/order_cat/{id}/qr_codes', 'QrCodeController@batchDelete');
Route::get('/qr_codes', 'HomeController@allQrCodes');

Route::get('/recycle_orders', 'RecycleOrderController@index');
Route::post('/recycle_orders', 'RecycleOrderController@store');
Route::get('/recycle_orders/{id}', 'RecycleOrderController@show')->name('recycle_orders');
Route::post('/recycle_orders/{id}', 'RecycleOrderController@update');
Route::delete('/recycle_orders/{id}', 'RecycleOrderController@destroy');
Route::get('/recycle_orders/{id}/qr_codes', 'RecycleOrderController@qrCodes');
Route::get('/recycle_orders/{id}/print_qr_code/{count}', 'RecycleOrderController@printQrCode');

Route::group(['middleware' => ['can:is-admin']], function () {
    Route::get('/users', 'UserController@index');
    Route::get('/create_user', 'UserController@createUser');
    Route::post('/users', 'UserController@store');
    Route::get('/users/{id}', 'UserController@show')->name('users');
    Route::post('/users/{id}', 'UserController@update');
    Route::delete('/users/{id}', 'UserController@destroy');

    Route::get('/cats', 'CategoryController@index');
    Route::get('/create_cat', 'CategoryController@createCat');
    Route::post('/cats', 'CategoryController@store');
    Route::get('/cats/{key}', 'CategoryController@show')->name('cats');
    Route::post('/cats/{key}', 'CategoryController@update');
    Route::delete('/cats/{key}', 'CategoryController@destroy');

});

Route::post('/app_login', 'AppController@login');
Route::post('/app_update_status', 'AppController@updateStatus');
Route::post('/wati', 'HomeController@wati');