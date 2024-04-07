<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationTimeSlot extends Model
{
    use HasFactory;

    protected $table = 'reservation_time_slot';

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
