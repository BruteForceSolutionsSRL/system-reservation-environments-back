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

Route::controller(ReservationController::class)->group(function() {
    Route::middleware('sanitize:api')->patch('/reservation/assign/{id}', 'assign');
    Route::get('/reservations', 'index');
    Route::get('/reservation/{reservationId}', 'show');
    Route::middleware('sanitize:api')->put('/reservation/reject/{reservationId}', 'rejectReservation');
    Route::middleware('sanitize:api')->post('/reservation', 'store');
});

Route::controller(ClassroomController::class)->group(function() {
    Route::middleware('sanitize:api')->post('/classroom', 'store');
    Route::get('/classrooms/block/{blockId}','classroomsByBlock');
    Route::get('/classrooms', 'list');
});

Route::controller(TeacherSubjectController::class)->group(function() {
    Route::get('/subjects/teacher/{teacherId}', 'subjectsByTeacher');
    Route::get('/teachers/subject/{universitySubjectID}', 'teachersBySubject');
    Route::get('/groups', 'index');
});

Route::get('/classroomtypes', [ClassroomTypeController::class, 'list']);
Route::middleware('sanitize:api')->post('/notification', [NotificationController::class ,'store']);
Route::get('/blocks', [BlockController::class, 'list']);
Route::get('/timeslots', [TimeSlotController::class, 'list']);

