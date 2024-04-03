<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with([
            'reservationStatus:id,status',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,teacher_id,university_subject_id',
            'teacherSubjects.teacher:id,person_id',
            'teacherSubjects.teacher.person:id,name,last_name,email,phone_number',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,floor,block_id,classroom_type_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return ReservationController::formatOutput($reservation);
        });

        return response()->json($formattedReservations, 200);
    }

    private function formatOutput($reservation) {
        return [
            'id' => $reservation->id,
            'numberOfStudents' => $reservation->number_of_students,
            'date' => $reservation->date,
            'reason' => $reservation->reason,
            'createdAt' => $reservation->created_at,
            'updatedAt' => $reservation->updated_at,
            'reservationStatus' => $reservation->reservationStatus,
            'schedule' => $reservation->timeSlots->map(function ($timeSlot) {
                return [
                    'id' => $timeSlot->id,
                    'time' => $timeSlot->time
                ];
            }),
            'asignament' => $reservation->teacherSubjects->map(function ($teacherSubject) {
                return [
                    'id' => $teacherSubject->id,
                    'groupNumber' => $teacherSubject->group_number,
                    'teacher' => [
                        'id' => $teacherSubject->teacher->id,
                        'person' => $teacherSubject->teacher->person
                    ],
                    'universitySubject' => $teacherSubject->universitySubject
                ];
            }),
            'classrooms' => $reservation->classrooms->map(function ($classroom) {
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'capacity' => $classroom->capacity,
                    'floor' => $classroom->floor,
                    'block' => $classroom->block,
                    'classroomType' => $classroom->classroomType
                ];
            })
        ];
    }

    public function show($id)
    {
        $reservation = Reservation::with([
            'reservationStatus:id,status',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,teacher_id,university_subject_id',
            'teacherSubjects.teacher:id,person_id',
            'teacherSubjects.teacher.person:id,name,last_name,email,phone_number',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,floor,block_id,classroom_type_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->findOrFail($id);

        if ($reservation == null) {
            return response()->json(['error'
                    => 'There is no reservation, try it later?'], 404);
        }

        return ReservationController::formatOutput($reservation);
    }

    public function rejectReservation($id) {
        $reservation = Reservation::findOrFail($id);

        if ($reservation == null) {
            return response()->json(['error'
                    => 'There is no reservation, try it later?'], 404);
        }

        if ($reservation->reservation_status_id == 2) {
            return response()->json(['error'
                    => 'This request has already been rejected'], 500);
        }

        $reservation->reservation_status_id = 2;
        $reservation->save();

        return response()->json(['error'
                    => 'Request rejected'], 200);
    }
}
