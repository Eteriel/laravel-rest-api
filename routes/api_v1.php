<?php

use Illuminate\Http\Request;

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
Route::group(['prefix' => 'item', 'as' => 'item.'], function () {
    Route::post('add/{order}/{item}', 'Api\v1\OrderController@addItem')->name('add');
    Route::delete('remove/{order}/{item}', 'Api\v1\OrderController@removeItem')->name('remove');
});


Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
    Route::get('{order}', 'Api\v1\OrderController@showOrder')->name('show');
    Route::post('create', 'Api\v1\OrderController@createOrder')->name('create');
//    Route::delete('{order}', 'Api\v1\OrderController@deleteOrder')->name('delete');

    Route::put('{order}/process', 'Api\v1\OrderController@processOrder')->name('process');
    Route::put('{order}/transfer', 'Api\v1\OrderController@transferOrder')->name('transfer');
    Route::put('{order}/complete', 'Api\v1\OrderController@completeOrder')->name('complete');
    Route::put('{order}/cancel', 'Api\v1\OrderController@cancelOrder')->name('cancel');
});