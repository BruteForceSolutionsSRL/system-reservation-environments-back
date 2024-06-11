<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\UniversitySubjectImpl;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response;

class UniversitySubjectController extends Controller
{
    private $universitySubjectService;

    public function __construct()
    {
        $this->universitySubjectService = new UniversitySubjectImpl();
    }

    /**
     * get all the subjects registered in the system
     *
     * @return array
     */
    public function list(): Response
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
