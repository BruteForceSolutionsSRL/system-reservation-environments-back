<?php
namespace App\Repositories;

use App\Models\AcademicPeriod;
use Carbon\Carbon; 

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

    public function getActualAcademicPeriod(int $facultyId): array 
    {
        $now = Carbon::now();
        $now = $now->format('Y-m-d');
        return $this->formatOutput(
            $this->model::where('faculty_id', $facultyId)
                ->where('initial_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->get()->first()
        );
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