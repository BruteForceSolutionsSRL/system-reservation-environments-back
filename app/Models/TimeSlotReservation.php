<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlotReservation extends Model
{
    use HasFactory;

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
