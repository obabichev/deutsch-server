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

Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('register', 'Auth\RegisterController@register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('words', 'WordController@index');
    Route::get('words/{word}', 'WordController@show');
    Route::post('words', 'WordController@store');
    Route::post('words/search', 'WordController@search');
    Route::put('words/{word}', 'WordController@update');
    Route::delete('words/{word}', 'WordController@delete');

    Route::get('glossary', 'GlossaryController@index');
    Route::get('glossary/{glossary}', 'GlossaryController@show');
    Route::post('glossary', 'GlossaryController@store');
    Route::put('glossary/{glossary}', 'GlossaryController@update');
    Route::delete('glossary/{id}', 'GlossaryController@delete');

    Route::post('translation/search', 'TranslationController@search');

    Route::post('glossarycard', 'GlossaryCardController@store');
    Route::delete('glossarycard/{id}', 'GlossaryCardController@delete');

    Route::get('wordprogress', 'WordProgressController@index');
    Route::post('wordprogress', 'WordProgressController@store');
    Route::put('wordprogress/{wordProgress}', 'WordProgressController@update');
});
