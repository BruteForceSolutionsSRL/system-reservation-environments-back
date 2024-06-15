<?php

namespace App\Http\Controllers;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
}; 

use App\Service\ServiceImplementation\ClassroomTypeServiceImpl; 

use Exception;

class ClassroomTypeController extends Controller
{
    private $classroomTypeService; 
    public function __construct()
    {
        $this->classroomTypeService = new ClassroomTypeServiceImpl();
    }
    
    /**
     * Retrieve all classroom types
     * @param Request $request 
     * @return Response
     */
    public function list(Request $request): Response
    {
        try {
            return response()->json(
                $this->classroomTypeService->getAllClassroomType(), 
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