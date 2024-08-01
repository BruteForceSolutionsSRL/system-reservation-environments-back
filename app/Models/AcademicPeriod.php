<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasFactory;
    protected $table = 'academic_periods';

    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function faculty() 
    {
        return $this->belongsTo(Faculty::class);
    }

    public function academicManagement()
    {
        return $this->belongsTo(AcademicManagement::class);
    }
}
