<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\StudyPlanServiceImpl;

use Illuminate\Http\{
    Request,
    JsonResponse as Response
};

use Illuminate\Support\Facades\Validator;

class StudyPlanController extends Controller
{
    private $studyPlanService; 

    public function __construct() 
    {
        $this->studyPlanService = new StudyPlanServiceImpl();
    }

    public function list(): Response
    {
        try {
            return response()->json($this->studyPlanService->getAllStudyPlans(), 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Se ha producido un error en el servidor.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function listForAcademicPeriod(int $academicPeriodId): Response 
    {
        try {
            return response()->json(
                $this->studyPlanService->getStudyPlans(
                    [
                        'academic_period_id' => $academicPeriodId
                    ]
                ), 
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Se ha producido un error en el servidor.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
