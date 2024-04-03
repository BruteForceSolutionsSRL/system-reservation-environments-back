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

Route::get('reservations', 'App\Http\Controllers\ReservationController@index');
Route::get('reservation/{id}', 'App\Http\Controllers\ReservationController@show');
Route::put('reservation/reject/{id}', 'App\Http\Controllers\ReservationController@rejectReservation');

Route::post('notification', 'App\Http\Controllers\NotificationController@store');
