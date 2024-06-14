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
    PersonController,
    AuthController,
    UniversitySubjectController
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

/**
 * Permisos
 * request_reserve => solicitud de reserva (docente)
 * reservation_handling => atender solicitudes (administrador)
 * availability => ver disponibilidad (all)
 * classrooms_statistics => ver estadisticas de ambientes (all)
 * notify => notificaciones solo se protege el post del controlador (all) 
 * report => generar reportes de uso de ambiente (Administrador) 
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 

Route::controller(AuthController::class)->group(function() {
    Route::post('/login', 'authenticate');
    Route::post('/register', 'register');
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('/logout', 'logout');
        Route::post('/get-user', 'getUser');
    });
});


Route::controller(ReservationReasonController::class)->group(function() {
    Route::middleware('jwt.verify:request_reserve')->get('/reservations/reasons', 'list');
});


Route::controller(ReservationStatusController::class)->group(function() {
    Route::middleware('jwt.verify:report')->get('/reservations/statuses', 'list');
});

Route::controller(ReservationController::class)->group(function() {
    Route::middleware('jwt.verify')->get('/reservations', 'list');
    Route::middleware('jwt.verify:reservation_handling')->get('/reservations/pending', 'getPendingRequests');
    Route::middleware('jwt.verify')->get('/reservations/teacher/{teacherId}/open', 'listRequestsByTeacher');
    Route::middleware('jwt.verify')->get('/reservations/teacher/{teacherId}', 'listAllRequestsByTeacher');
    Route::middleware('jwt.verify:history')->get('/reservations/history', 'getAllRequestsExceptPending');
    Route::middleware('jwt.verify:history')->get('/reservations/history/teacher/{teacherId}', 'getAllRequestsExceptPendingByTeacher');
    Route::middleware('jwt.verify:report')->get('/reservations/reports', 'getReports');
    Route::middleware('jwt.verify')->get('/reservations/{reservationId}', 'show');
    Route::middleware('jwt.verify:reservation_handling')->get('/reservations/{reservationId}/conflicts', 'getConflicts');
    Route::middleware('jwt.verify')->get('/reservations/classroom/{classroomId}','getAllReservationsByClassroom');

    Route::middleware('sanitize:api')->middleware('jwt.verify:reservation_handling')->patch('/reservations/{reservationId}/reject', 'rejectReservation');
    Route::middleware('sanitize:api')->middleware('jwt.verify:reservation_handling')->patch('/reservations/{reservationId}/assign', 'assign');
    Route::middleware('sanitize:api')->middleware('jwt.verify')->patch('/reservations/{reservationId}/cancel', 'cancelRequest');

    Route::middleware('sanitize:api')->middleware('jwt.verify:request_reserve')->post('/reservations', 'store');
});

Route::controller(ClassroomStatusController::class)->group(function() {
    Route::middleware('jwt.verify')->get('/classrooms/statuses', 'list');
});

Route::controller(ClassroomTypeController::class)->group(function() {
    Route::middleware('jwt.verify')->get('/classrooms/types', 'list');
});

Route::controller(ClassroomController::class)->group(function() {
    Route::middleware('jwt.verify')->get('/classrooms', 'list');
    Route::middleware('jwt.verify')->get('/classrooms/block/{blockId}','classroomsByBlock'); // ALL
    Route::middleware('jwt.verify')->get('/classrooms/block/{blockId}/available', 'availableClassroomsByBlock');
    Route::middleware('jwt.verify')->get('/classrooms/last-validated', 'retriveLastClassroom');
    Route::middleware('jwt.verify')->get('/classrooms/statistics/list','getAllClassroomsWithStatistics');

    Route::middleware('jwt.verify')->delete('/classrooms/delete/{classroomId}','destroy');

    Route::group(['middleware' => ['sanitize:api','jwt.verify:request_reserve']], function() {
        Route::post('/classrooms/reservation/suggest', 'suggestClassrooms');
    });

    Route::middleware('sanitize:api')->middleware('jwt.verify:availability')->post('/classrooms/disponibility', 'getClassroomByDisponibility');
    Route::middleware('sanitize:api')->middleware('jwt.verify')->post('/classrooms', 'store');
    Route::middleware('sanitize:api')->middleware('jwt.verify')->post('/classrooms/stats', 'getClassroomStats');

    Route::middleware('sanitize:api')->middleware('jwt.verify')->put('/classrooms/{classroomId}', 'update');
});

//Agrupacion
Route::controller(TeacherSubjectController::class)->group(function() {
    Route::middleware('jwt.verify:request_reserve')->get('/teacher-subjects/teacher/{teacherId}', 'subjectsByTeacher');
    Route::middleware('jwt.verify:request_reserve')->get('/teacher-subjects/subject/{universitySubjectID}', 'teachersBySubject'); // ALL
});

Route::controller(NotificationController::class)->group(function() {
    Route::middleware('sanitize:api')->middleware('jwt.verify:notify')->post('/notifications/send', 'store');

    Route::middleware('jwt.verify')->get('/notifications/inbox', 'list');
    Route::middleware('jwt.verify')->get('/notifications/inbox/{notificationId}', 'show');
});

Route::controller(BlockController::class)->group(function() {
    Route::middleware('jwt.verify')->get('/blocks', 'list');
    Route::get('/blocks/{block_id}', 'show'); 
    Route::get('/blocks/{block_id}/statistics', 'getBlockStatistics'); 

    Route::middleware('sanitize:api')->middleware('jwt.verify:block_register')->post('/blocks', 'store'); 

    Route::middleware('sanitize:api')->middleware('jwt.verify:block_update')->put('/blocks/{block_id}', 'update');

    Route::middleware('jwt.verify:block_remove')->delete('/blocks/{block_id}', 'destroy');  
});

Route::controller(TimeSlotController::class)->group(function() {
    Route::middleware('jwt.verify')->get('/timeslots', 'list');
});

Route::controller(PersonController::class)->group(function() {
    Route::get('/users/teachers', 'listTeachers');
    Route::get('/users', 'list');
    Route::get('/users/{personId}', 'show');
});

Route::controller(UniversitySubjectController::class)->group(function() {
    Route::middleware('jwt.verify:report')->get('/university-subjects', 'list');
});
