<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function classroomType()
    {
        return $this->belongsTo(ClassroomType::class);
    }

    public function reservationClassrooms()
    {
        return $this->hasMany(ReservationClassroom::class);
    }
}
