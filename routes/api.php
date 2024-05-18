<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    TeacherSubjectController,
    TimeSlotController,
    BlockController,
    ClassroomController,
    ClassroomTypeController,
    NotificationController,
    ReservationController,
    ReservationReasonController,
    ClassroomStatusController
};

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
    Route::get('/reservations', 'index');
    Route::get('/reservations-history', 'getAllRequestsExceptPending');
    Route::get('/pending-requests', 'getPendingRequests');
    Route::get('/reservations/{teacherId}', 'listRequestsByTeacher');
    Route::get('/all-reservations/{teacherId}', 'listAllRequestsByTeacher');
    Route::get('/reservations-history/teacher/{teacherId}', 'getAllRequestsExceptPendingByTeacher');
    Route::get('/reservation/{reservationId}', 'show');
    Route::middleware('sanitize:api')->patch('/reservation/reject/{reservationId}', 'rejectReservation');
    Route::middleware('sanitize:api')->patch('/reservation/assign/{reservationId}', 'assign');
    Route::middleware('sanitize:api')->patch('/reservation/cancel/{reservationId}', 'cancelRequest');
    Route::middleware('sanitize:api')->post('/reservation', 'store');
    Route::get('/reservation/conflicts/{reservationId}', 'getConflicts');
});

Route::controller(ClassroomController::class)->group(function() {
    Route::middleware('sanitize:api')->post('/classroom', 'store');
    Route::get('/classrooms/block/{blockId}','classroomsByBlock');
    Route::get('/avaible-classrooms/block/{blockId}', 'availableClassroomsByBlock');
    Route::get('/classrooms', 'list');
    Route::post('/classroom/disponibility', 'getClassroomByDisponibility');
    Route::post('/reservation/suggest', 'suggestClassrooms');
    Route::put('/classroom/{classroomId}', 'update');
    Route::get('/classroom/last-validated', 'retriveLastClassroom');
});

Route::controller(TeacherSubjectController::class)->group(function() {
    Route::get('/subjects/teacher/{teacherId}', 'subjectsByTeacher');
    Route::get('/teachers/subject/{universitySubjectID}', 'teachersBySubject');
});

Route::controller(ClassroomStatusController::class)->group(function() {
    Route::get('/classroom-statuses', 'list');
});

Route::get('/classroomtypes', [ClassroomTypeController::class, 'list']);
Route::middleware('sanitize:api')->post('/notification', [NotificationController::class ,'store']);
Route::get('/blocks', [BlockController::class, 'list']);
Route::get('/timeslots', [TimeSlotController::class, 'list']);

Route::get('/reservation-reasons', [ReservationReasonController::class, 'index']);

