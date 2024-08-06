<?php

namespace App\Service\ServiceImplementation;

use App\Repositories\StudyPlanRepository; 

class StudyPlanServiceImpl
{
    private $studyPlanRepository; 

    public function __construct()
    {
        $this->studyPlanRepository = new StudyPlanRepository();
    }

    public function getAllStudyPlans(): array
    {
        return $this->studyPlanRepository->getAllStudyPlans(); 
    }

    public function obtainStudyPlansBySetOfFaculties(array $data): array 
    {
        return $this->studyPlanRepository->getStudyPlans($data);
    }

    public function attachAcademicPeriod(int $studyPlanId, int $academicPeriodId) 
    {
        $this->studyPlanRepository->addAcademicPeriod($studyPlanId, $academicPeriodId);
    }
}