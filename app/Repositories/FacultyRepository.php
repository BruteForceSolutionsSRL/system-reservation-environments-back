<?php
namespace App\Repositories;

use App\Models\Faculty; 

class FacultyRepository
{
    protected $model; 
    
    public function __construct()
    {
        $this->model = Faculty::class; 
    }

    public function getFacultyByID(int $facultyId): array 
    {
        return $this->formatOutput($this->model::find($facultyId));
    }

    public function formatOutput($faculty): array 
    {
        return [
            'name' => $faculty->name,
            'academic_period_id' => $faculty->academic_period_id, 
            'academic_period_name' => $faculty->academicPeriod->name,
        ];
    }
}