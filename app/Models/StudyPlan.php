<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyPlan extends Model
{
    use HasFactory;
    protected $table = 'study_plans';

    public function universitySubjects()
    {
        return $this->belongsToMany(UniversitySubject::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function studyPlanUniversityPlans()
    {
        return $this->hasMany(StudyPlanUniversitySubject::class);
    }
}
