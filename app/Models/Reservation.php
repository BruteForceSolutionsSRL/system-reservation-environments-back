<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_of_students',
        'repeat',
        'date',
        'reason',
        'reservation_status_id'
    ];

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function reservationTimeSlots()
    {
        return $this->hasMany(ReservationTimeSlot::class);
    }

    public function reservationStatus()
    {
        return $this->belongsTo(ReservationStatus::class);
    }

    public function reservationTeacherSubjects()
    {
        return $this->hasMany(ReservationTeacherSubject::class);
    }

    public function classroomReservations()
    {
        return $this->hasMany(ClassroomReservation::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class);
    }

    public function teacherSubjects()
    {
        return $this->belongsToMany(TeacherSubject::class);
    }

    public function timeSlots()
    {
        return $this->belongsToMany(TimeSlot::class);
    }

    public function reservationReason()
    {
        return $this->belongsTo(ReservationReason::class);
    }
}
