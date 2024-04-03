<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\UniversitySubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\BlockController;

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

//Route::middleware('api:cors')->group(function() {
    Route::post('reservation/assign', 'App\Http\Controllers\ReservationController@assign');
    Route::middleware('sanitize:api')->post('/classroom', 'App\Http\Controllers\ClassroomController@store');
    Route::get('/classroomtypes', 'App\Http\Controllers\ClassroomTypeController@list');
    Route::get('/classrooms/{id}','App\Http\Controllers\ClassroomController@classroomsByBlock');
//});

Route::get('/subjects', [UniversitySubjectController::class, 'index']);
Route::get('/subjects/teacher/{id}', [UniversitySubjectController::class, 'subjectsCommonTeachers']);
Route::get('/teachers/{subjectID}', [TeacherController::class, 'teachersCommonSubjects']);
Route::get('/periods', [TimeSlotController::class, 'index']);
Route::get('/blocks', [BlockController::class, 'index']);