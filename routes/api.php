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
use App\Http\Controllers\ReservationReasonController;

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
    Route::patch('/reservation/assign/{reservationId}', 'assign');
    Route::get('/reservations', 'index');
    Route::get('/pending-requests', 'getPendingRequests');
    Route::get('/reservations/{teacherId}', 'listRequestsByTeacher');
    Route::get('/all-reservations/{teacherId}', 'listAllRequestsByTeacher'); // para el historial ?
    Route::get('/reservation/{reservationId}', 'show');
    Route::middleware('sanitize:api')->patch('/reservation/reject/{reservationId}', 'rejectReservation');
    Route::middleware('sanitize:api')->patch('/reservation/cancel/{reservationId}', 'cancelRequest');
    Route::middleware('sanitize:api')->post('/reservation', 'store');
    Route::get('/reservation/conflicts/{reservationId}', 'getConflicts');
});

Route::controller(ClassroomController::class)->group(function() {
    Route::middleware('sanitize:api')->post('/classroom', 'store');
    Route::get('/classrooms/block/{blockId}','classroomsByBlock');
    Route::get('/avaible-classrooms/block/{blockId}', 'avaibleClassroomsByBlock');
    Route::get('/classrooms', 'list');

    Route::post('/classroom/disponibility', 'getClassroomByDisponibility');
});

Route::controller(TeacherSubjectController::class)->group(function() {
    Route::get('/subjects/teacher/{teacherId}', 'subjectsByTeacher');
    Route::get('/teachers/subject/{universitySubjectID}', 'teachersBySubject');
});

Route::get('/classroomtypes', [ClassroomTypeController::class, 'list']);
Route::middleware('sanitize:api')->post('/notification', [NotificationController::class ,'store']);
Route::get('/blocks', [BlockController::class, 'list']);
Route::get('/timeslots', [TimeSlotController::class, 'list']);

Route::get('/reservation-reasons', [ReservationReasonController::class, 'index']);

