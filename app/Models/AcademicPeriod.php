<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasFactory;
    protected $table = 'academic_periods';

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
