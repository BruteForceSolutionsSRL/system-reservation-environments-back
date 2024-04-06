<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClassroomType;

class ClassroomTypeController extends Controller
{
    // Retrieves all classroom types 
    public function list() 
    {
        $classroomTypes = ClassroomType::all();
        $result = array();
        foreach ($classroomTypes as $classroomType) {
            $element = [
                'id'=>$classroomType->id,
                'value'=>$classroomType->description 
            ];
            array_push($result, $element);
        }
        return response()->json($result, 200);
    }
}
