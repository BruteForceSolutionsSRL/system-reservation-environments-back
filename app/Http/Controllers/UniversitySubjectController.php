<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\UniversitySubjectImpl;
use Exception;
use Illuminate\Http\{
    JsonResponse as Response,
    Request
};

class UniversitySubjectController extends Controller
{
    private $universitySubjectService;

    public function __construct()
    {
        $this->universitySubjectService = new UniversitySubjectImpl();
    }

    /**
     * get all the subjects registered in the system
     * @param Request $request
     * @return array
     */
    public function list(Request $request): Response
    {
        try {
            return response()->json(
                $this->universitySubjectService->getAllUniversitySubject(),
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}