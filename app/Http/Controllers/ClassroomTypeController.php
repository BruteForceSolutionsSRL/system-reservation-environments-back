<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClassroomType;

class ClassroomTypeController extends Controller
{
    // Retrieves all classroom types 
    public function list() 
    {
        try {
            $classroomTypes = ClassroomType::all()
            ->map(
                function ($classroomType) 
                {
                    return [
                        'type_id' => $classroomType->id, 
                        'type_name' => $classroomType->description
                    ];
                }
            );
            return response()->json($classroomTypes, 200);
        } catch (\Exception $e) {
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
