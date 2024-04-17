<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Classroom; 
use App\Models\Block; 
use App\Models\ClassroomType;

class ClassroomController extends Controller
{
    /**
     * @param
     *  None
     * @return 
     *  All classrooms
     */
    public function list() 
    {
        // $classrooms = Classroom::all();
        // $status = 200; 
        // return response()->json($classrooms, $status);
        $classroomsCollection = [
            [
                ['classroom_id' => 1,'classroom_name' => '692A', 'capacity' => 120, 'floor_number' => 2],
                ['classroom_id' => 2,'classroom_name' => '692B', 'capacity' => 120, 'floor_number' => 2],
                ['classroom_id' => 3,'classroom_name' => '692C', 'capacity' => 120, 'floor_number' => 2],
                ['classroom_id' => 4,'classroom_name' => '693D', 'capacity' => 120, 'floor_number' => 3],
                ['classroom_id' => 5,'classroom_name' => 'Auditorio', 'capacity' => 360, 'floor_number' => 3]
            ],
            [
                ['classroom_id' => 6,'classroom_name' => 'Laboratorio 3', 'capacity' => 30, 'floor_number' => 2],
                ['classroom_id' => 7,'classroom_name' => 'Laboratorio 4', 'capacity' => 35, 'floor_number' => 2]
            ],
            [
                ['classroom_id' => 9,'classroom_name' => '625C', 'capacity' => 25, 'floor_number' => 2],
                ['classroom_id' => 10,'classroom_name' => '625D', 'capacity' => 25, 'floor_number' => 2]
            ],
            [
                ['classroom_id' => 11,'classroom_name' => '618', 'capacity' => 20, 'floor_number' => 0],
                ['classroom_id' => 12,'classroom_name' => '619', 'capacity' => 20, 'floor_number' => 0],
                ['classroom_id' => 13,'classroom_name' => '619A', 'capacity' => 15, 'floor_number' => 0],
            ]
        ];

        return response()->json($classroomsCollection[rand(0, 3)], 200);
    }
    /**
     * @covers: 
     * To retrieve data about a environment
     */
    public function index() 
    {
    }

    public function classroomsByBlock($blockId)
    {
        try {
            $classrooms = Classroom::select('id', 'name', 'capacity', 'floor')
                ->where('block_id', $blockId)
                ->get()
                ->map(function ($classroom){
                    return [
                        'classroom_id' => $classroom->id,
                        'name' => $classroom->name,
                        'capacity' => $classroom->capacity,
                        'floor' => $classroom->floor,
                    ];
                });

            return response()->json($classrooms, 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 500); 
        }    
    }

    /**
     * @param
     * Request (body): 
     * {
     * 'name': str, 
     * 'capacity': 'number', 
     * 'classroomTypeID': int, 
     * 'blockID': int
     * 'floor': int
     * }
     */
    public function store(Request $request) 
    {
        try {
            $classroom_name = $request->input('classroom_name'); 
            $capacity = $request->input('capacity'); 
            $type_id = $request->input('type_id'); 
            $block_id = $request->input('block_id'); 
            $floor_number = $request->input('floor_number'); 

            if ($classroom_name==null || $classroom_name=="") 
                return response()->json(["message"=>"El nombre es nulo o vacio"], 406);
            if ($capacity<0) 
                return response()->json(["message"=>"La capacidad es un numero negativo"], 406);
            if ($type_id>5)
                return response()->json(["message"=>"El tipo de ambiente no existe"], 404);
            if ($block_id>7) 
                return response()->json(["message"=>"El bloque seleccionado no existe"], 404);
            if ($floor_number>10)
                return response()->json(["message"=>"El piso es mayor al numero de pisos que tiene el bloque/edificio"], 406);
            return response()->json(["message"=>"se registro de manera correcta el nuevo ambiente"], 200);
        } catch (Exception $e) {
            return response()->json(["message"=>"Ocurrio un error del servidor"], 500);
        }
    }
    /**
     * @covers
     * To cancel assigned types. 
     */
    public function update(Request $request, $id) 
    {
    }

    /**
     * @covers
     * IDK
     */
    public function destroy($id) 
    {
    }
}