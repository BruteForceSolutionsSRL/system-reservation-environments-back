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
    Route::get('/reservations', 'list');
    Route::get('/reservations/pending', 'getPendingRequests');
    Route::get('/reservations/teacher/{teacherId}/open', 'listRequestsByTeacher');
    Route::get('/reservations/teacher/{teacherId}', 'listAllRequestsByTeacher'); 
    Route::get('/reservations/{reservationId}', 'show');
    Route::get('/reservations/{reservationId}/conflicts', 'getConflicts');
    
    Route::middleware('sanitize:api')->patch('/reservations/{reservationId}/reject/', 'rejectReservation');
    Route::middleware('sanitize:api')->patch('/reservations/{reservationId}/assign/', 'assign');
    Route::middleware('sanitize:api')->patch('/reservations/{reservationId}/cancel/', 'cancelRequest');
    
    Route::middleware('sanitize:api')->post('/reservations', 'store');    
});

Route::controller(ReservationReasonController::class)->group(function() {
    Route::get('/reservations/reasons', 'list');
});

Route::controller(ClassroomController::class)->group(function() {
    Route::get('/classrooms', 'list');
    Route::get('/classrooms/block/{blockId}','classroomsByBlock');
    Route::get('/classrooms/block/{blockId}/available', 'availableClassroomsByBlock');
    Route::get('/classrooms/last-validated', 'retriveLastClassroom');

    Route::middleware('sanitize:api')->post('/classrooms/disponibility', 'getClassroomByDisponibility');
    Route::middleware('sanitize:api')->post('/classrooms/reservation/suggest', 'suggestClassrooms');
    Route::middleware('sanitize:api')->post('/classrooms', 'store');
    
    Route::middleware('sanitize:api')->put('/classrooms/{classroomId}', 'update');
});

Route::controller(ClassroomStatusController::class)->group(function() {
    Route::get('/classrooms/statuses', 'list');
});

Route::controller(ClassroomTypeController::class)->group(function() {
    Route::get('/classrooms/types', 'list');
});

Route::controller(TeacherSubjectController::class)->group(function() {
    Route::get('/teacher-subjects/teacher/{teacherId}', 'subjectsByTeacher');
    Route::get('/teacher-subjects/subject/{universitySubjectID}', 'teachersBySubject');
});

Route::controller(NotificationController::class)->group(function() {
    Route::middleware('sanitize:api')->post('/notifications', 'store');
});

Route::controller(BlockController::class)->group(function() {
    Route::get('/blocks', 'list');
});

Route::controller(TimeSlotController::class)->group(function() {
    Route::get('/timeslots', 'list');
});
