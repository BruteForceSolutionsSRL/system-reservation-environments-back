<?php
namespace App\Repositories;

use App\Models\AcademicPeriod; 

class AcademicPeriodRepository 
{
    protected $model; 

    public function __construct()
    {
        $this->model = AcademicPeriod::class;
    }

    public function getAcademicPeriod(string $date): array 
    {
        return [];
    }

    public function formatOutput($academicPeriod) 
    {
        return [
            'academic_period_id' => $academicPeriod->id,
            'name' => $academicPeriod->name,
            'initial_date' => $academicPeriod->initial_date,
            'end_date' => $academicPeriod->end_date,
            'activated' => $academicPeriod->activated
        ];
    }
}