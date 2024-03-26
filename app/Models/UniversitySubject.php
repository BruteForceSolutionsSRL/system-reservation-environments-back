<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversitySubject extends Model
{
    use HasFactory;
<<<<<<< HEAD
=======

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
>>>>>>> develop
}
