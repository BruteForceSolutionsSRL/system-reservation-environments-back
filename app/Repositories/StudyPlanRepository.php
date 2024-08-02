<?php 
namespace App\Repositories;

use App\Models\StudyPlan;

class StudyPlanRepository
{
    protected $model; 

    public function __construct()
    {
        $this->model = StudyPlan::class;
    }

    public function getAllStudyPlans(): array 
    {
        return $this->model::all()->map(
            function ($studyPlan) 
            {
                return $this->formatOutput($studyPlan);
            }
        )->toArray();
    }

    public function getStudyPlans(array $data): array  
    {
        $query = StudyPlan::with(['academicPeriod:id,name']);
        
        if (array_key_exists('department_ids', $data)) {
            $query->whereHas(
                'universitySubjects.department', 
                function ($query) use ($data) 
                {
                    $query->whereIn('departments.id', $data['department_ids']);
                }
            );
        }
        return $query->get()->map(
            function ($studyPlan) {
                return $this->formatOutput($studyPlan);
            }
        )->toArray();
    }

    private function formatOutput($studyPlan): array 
    {
        if ($studyPlan == null) return [];
        // aqui falta las materias de una carrera
        return [
            'study_plan_id' => $studyPlan->id, 
            'name' => $studyPlan->name, 
            'academic_period_id' => $studyPlan->academic_period_id, 
            'academic_period_name' => $studyPlan->academicPeriod->name,
        ];
    }
}