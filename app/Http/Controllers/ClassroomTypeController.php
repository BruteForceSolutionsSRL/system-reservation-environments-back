<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response; 

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
     * Explain: Retrieve all classroom types 
     * @return \Response
     */
    public function list(): Response
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
