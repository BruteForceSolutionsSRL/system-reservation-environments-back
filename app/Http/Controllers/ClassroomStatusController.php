<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\ClassroomServiceImpl;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
};
use Exception; 

class ClassroomStatusController extends Controller
{
    private $classroomService; 
    public function __construct()
    {
        $this->classroomService = new ClassroomServiceImpl(); 
    }
    
    /**
     * Retrieve a JSON to list all statuses for classroom
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        try {
            return response()->json(
                $this->classroomService->getClassroomStatuses(), 
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