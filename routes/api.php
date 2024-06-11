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
    ClassroomStatusController,
    ReservationStatusController,
    PersonController
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
Route::controller(ReservationReasonController::class)->group(function() {
    Route::get('/reservations/reasons', 'list');
});

Route::controller(ReservationStatusController::class)->group(function() {
    Route::get('/reservations/statuses', 'list');
});

Route::controller(ReservationController::class)->group(function() {
    Route::get('/reservations', 'list');
    Route::get('/reservations/pending', 'getPendingRequests');
    Route::get('/reservations/teacher/{teacherId}/open', 'listRequestsByTeacher');
    Route::get('/reservations/teacher/{teacherId}', 'listAllRequestsByTeacher'); 
    Route::get('/reservations/history', 'getAllRequestsExceptPending');
    Route::get('/reservations/history/teacher/{teacherId}', 'getAllRequestsExceptPendingByTeacher');
    Route::get('/reservations/reports', 'getReports');
    Route::get('/reservations/{reservationId}', 'show');
    Route::get('/reservations/{reservationId}/conflicts', 'getConflicts');
    Route::get('/reservations/classroom/{classroomId}','getAllReservationsByClassroom');

    Route::middleware('sanitize:api')->patch('/reservations/{reservationId}/reject', 'rejectReservation');
    Route::middleware('sanitize:api')->patch('/reservations/{reservationId}/assign', 'assign');
    Route::middleware('sanitize:api')->patch('/reservations/{reservationId}/cancel', 'cancelRequest');
    
    Route::middleware('sanitize:api')->post('/reservations', 'store');    
});

Route::controller(ClassroomStatusController::class)->group(function() {
    Route::get('/classrooms/statuses', 'list');
});

Route::controller(ClassroomTypeController::class)->group(function() {
    Route::get('/classrooms/types', 'list');
});

Route::controller(ClassroomController::class)->group(function() {
    Route::get('/classrooms', 'list');
    Route::get('/classrooms/block/{blockId}','classroomsByBlock');
    Route::get('/classrooms/block/{blockId}/available', 'availableClassroomsByBlock');
    Route::get('/classrooms/last-validated', 'retriveLastClassroom');
    Route::get('/classrooms/statistics/list','getAllClassroomsWithStatistics');

    Route::middleware('sanitize:api')->post('/classrooms/stats', 'getClassroomStats');

    Route::delete('/classrooms/delete/{classroomId}','destroy');

    Route::middleware('sanitize:api')->post('/classrooms/disponibility', 'getClassroomByDisponibility');
    Route::middleware('sanitize:api')->post('/classrooms/reservation/suggest', 'suggestClassrooms');
    Route::middleware('sanitize:api')->post('/classrooms', 'store');
    Route::middleware('sanitize:api')->post('/classrooms/stats', 'getClassroomStats');

    Route::middleware('sanitize:api')->put('/classrooms/{classroomId}', 'update');
});

Route::controller(TeacherSubjectController::class)->group(function() {
    Route::get('/teacher-subjects/teacher/{teacherId}', 'subjectsByTeacher');
    Route::get('/teacher-subjects/subject/{universitySubjectID}', 'teachersBySubject');
});

Route::controller(NotificationController::class)->group(function() {
    Route::get('/notifications/inbox/{personId}', 'list');
    Route::get('/notifications/inbox/{personId}/{notificationId}', 'show'); 
});

Route::controller(BlockController::class)->group(function() {
    Route::get('/blocks', 'list');
    Route::get('/blocks/{block_id}', 'show'); 
    Route::get('/blocks/{block_id}/statistics', 'getBlockStatistics'); 

    Route::middleware('sanitize:api')->post('/blocks', 'store'); 

    Route::middleware('sanitize:api')->put('/blocks/{block_id}', 'update');

    Route::delete('/blocks/{block_id}', 'destroy');  
});

Route::controller(TimeSlotController::class)->group(function() {
    Route::get('/timeslots', 'list');
});

Route::controller(PersonController::class)->group(function() {
    Route::get('/users/teachers', 'listTeachers');
    Route::get('/users', 'list');
    Route::get('/users/{personId}', 'show');
});
