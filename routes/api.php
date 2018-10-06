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

Route::prefix('tasks')->namespace('Api')->group(function () {
    Route::get('/', 'TodoController@tasks');
    Route::post('/', 'TodoController@store');
    Route::put('/{task}', 'TodoController@update');
    Route::delete('/{task}', 'TodoController@destroy');
});
