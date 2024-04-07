<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassroomTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReservationController;

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
    // Route::post('reservation/assign', 'App\Http\Controllers\ReservationController@assign');
    // Route::middleware('sanitize:api')->post('/classroom', 'App\Http\Controllers\ClassroomController@store');
    // Route::get('/classroomtypes', 'App\Http\Controllers\ClassroomTypeController@list');
    // Route::get('/classrooms/block/{blockId}','App\Http\Controllers\ClassroomController@classroomsByBlock');
    // Route::get('/classroom', 'App\Http\Controllers\ClassroomController@list');
//});

// Route::get('reservations', 'App\Http\Controllers\ReservationController@index');
// Route::get('reservation/{reservationId}', 'App\Http\Controllers\ReservationController@show');
// Route::middleware('sanitize:api')->put('reservation/reject/{reservationId}', 'App\Http\Controllers\ReservationController@rejectReservation');

// Route::middleware('sanitize:api')->post('notification', 'App\Http\Controllers\NotificationController@store');

// Route::get('/subjects/teacher/{teacherId}', [TeacherSubjectController::class, 'subjectsByTeacher']);
// Route::get('/teachers/subject/{universitySubjectID}', [TeacherSubjectController::class, 'teachersBySubject']);

Route::controller(ReservationController::class)->group(function() {
    Route::post('/reservation/assign', 'assign');
    Route::get('/reservations', 'index');
    Route::get('/reservation/{reservationId}', 'show');
    Route::middleware('sanitize:api')->put('/reservation/reject/{reservationId}', 'rejectReservation');
});

Route::controller(ClassroomController::class)->group(function() {
    Route::middleware('sanitize:api')->post('/classroom', 'store');
    Route::get('/classrooms/block/{blockId}','classroomsByBlock');
    Route::get('/classroom', 'list');
});

Route::controller(TeacherSubjectController::class)->group(function() {
    Route::get('/subjects/teacher/{teacherId}', 'subjectsByTeacher');
    Route::get('/teachers/subject/{universitySubjectID}', 'teachersBySubject');
});

Route::get('/classroomtypes', [ClassroomTypeController::class, 'list']);
Route::middleware('sanitize:api')->post('/notification', [NotificationController::class ,'store']);
Route::get('/blocks', [BlockController::class, 'list']);
Route::get('/timeslots', [TimeSlotController::class, 'list']);
Route::post('/reservation', [ReservationController::class, 'store']);
