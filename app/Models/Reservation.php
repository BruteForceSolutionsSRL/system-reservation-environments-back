<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;


    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function timeSlotReservations()
    {
        return $this->hasMany(TimeSlotReservation::class);
    }

    public function reservationStatus()
    {
        return $this->belongsTo(ReservationStatus::class);
    }

    public function reservationTeacherSubjects()
    {
        return $this->hasMany(ReservationTeacherSubject::class);
    }

    public function reservationClassrooms()
    {
        return $this->hasMany(ReservationClassroom::class);
    }
}
