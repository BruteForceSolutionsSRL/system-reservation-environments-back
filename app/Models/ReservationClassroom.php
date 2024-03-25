<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationClassroom extends Model
{
    use HasFactory;

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
