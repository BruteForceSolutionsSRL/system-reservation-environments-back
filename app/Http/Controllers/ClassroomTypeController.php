<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClassroomType;

class ClassroomTypeController extends Controller
{
    // Retrieves all classroom types 
    public function list() 
    {

        $result = array();
        $classroomTypes = array(
            ["id"=>1,"value"=>"Laboratotio"], 
            ["id"=>2,"value"=>"Aula"], 
            ["id"=>3,"value"=>"Coliseo"],
            ["id"=>4,"value"=>"Auditorio"]
        );
        foreach ($classroomTypes as $classroomType) {
            $element = [
                'type_id'=>$classroomType["id"],
                'type_name'=>$classroomType["value"] 
            ];
            array_push($result, $element);
        }
        return response()->json($result, 200);
    }
}
