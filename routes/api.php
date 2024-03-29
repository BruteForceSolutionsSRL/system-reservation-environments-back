<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::get('/ayuda', 'App\Http\Controllers\ReservationController@ayuda');
Route::post('reservation/assign', 'App\Http\Controllers\ReservationController@accept');
//Route::get('/ayuda', 'ReservationController@index');
//Route::middleware('auth:api')->get('/classroom/{id}', 'ClasroomControler@index');
//Route::middleware('auth:api')->post('/classroom', 'ClassroomController@store');
//Route::middleware('auth:api')->update('/classroom/{id}', 'ClassroomController@update'); 
//Route::middleware('auth:api')->delete('/classroom/{id}', 'ClassroomController@destroy'); 
//Route::middleware('auth:api')->get('/classroom', 'ClassroomController@list'); 
