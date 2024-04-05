<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationTeacherSubject extends Model
{
    use HasFactory;

    protected $table = 'reservation_teacher_subject';

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }
}
