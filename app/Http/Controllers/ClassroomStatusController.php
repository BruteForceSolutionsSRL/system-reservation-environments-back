<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\ClassroomServiceImpl;

use Illuminate\Http\JsonResponse as Response;
use Exception; 

class ClassroomStatusController extends Controller
{
    private $classroomService; 
    public function __construct()
    {
        $this->classroomService = new ClassroomServiceImpl(); 
    }
    public function list(): Response
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
