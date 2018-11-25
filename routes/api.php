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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('words', 'WordController@index');
Route::get('words/{word}', 'WordController@show');
Route::post('words', 'WordController@store');
Route::put('words/{word}', 'WordController@update');
Route::delete('words/{word}', 'WordController@delete');
