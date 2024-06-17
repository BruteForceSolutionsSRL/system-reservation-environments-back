<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomReservation extends Model
{
    use HasFactory;

    protected $table = 'classroom_reservation';

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
