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
        $classrooms = Classroom::select('id', 'name', 'capacity', 'floor')
            ->get()
            ->map(function ($classroom) {
                return $this->formatClassroomResponse($classroom);
            });
        return response()->json($classrooms, 200);
    }

    private function formatClassroomResponse($classroom)
    {
        return [
            'classroom_id' => $classroom->id,
            'classroom_name' => $classroom->name,
            'capacity' => $classroom->capacity,
            'floor' => $classroom->floor,
        ];
    }

    /**
     * @covers:
     * To retrieve data about a environment
     */
    public function index()
    {
    }

    public function avaibleClassroomsByBlock($blockId)
    {
        try {
            $classrooms = Classroom::select('id', 'name', 'capacity', 'floor')
                ->where('block_id', $blockId)
                ->whereNotIn('id', function ($query) use ($blockId) {
                    $query->select('C.id')
                        ->from('classrooms as C')
                        ->join('classroom_reservation as CR', 'C.id', '=', 'CR.classroom_id')
                        ->join('reservations as R', 'CR.reservation_id', '=', 'R.id')
                        ->where('C.block_id', $blockId)
                        ->where('R.reservation_status_id', 1)
                        ->where('R.date', '>=', now()->format('Y-m-d'));
                })->get()
                ->map(function ($classroom) {
                    return $this->formatClassroomResponse($classroom);
                });

            return response()->json($classrooms, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function classroomsByBlock($blockId)
    {
        try {
            $classrooms = Classroom::select('id', 'name', 'capacity', 'floor')
                ->where('block_id', $blockId)
                ->get()
                ->map(function ($classroom){
                    return $this->formatClassroomResponse($classroom);
                });

            return response()->json($classrooms, 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message'=>'Hubo un error en el servidor', 
                    'error' => $e->getMessage()
                ], 
                500
            ); 
        }    
    }

    /**
     * @param
     * Store a new classroom with the info below
     * Request (body): 
     * {
     * 'classroom_name': str, 
     * 'capacity': 'number', 
     * 'type_id': int, 
     * 'block_id': int
     * 'floor_number': int
     * }
     */
    public function store(Request $request) 
    {
        try {
            $validator = $this->validateClassroomData($request);
  
            if ($validator->fails()) {
                $message = ''; 
                foreach ($validator->errors()->all() as $value) 
                    $message = $message . $value . '\n';
                return response()->json(
                    ['message' => $message], 
                    400
                );
            }

            $data = $validator->validated();

            $block = Block::findOrFail($data['block_id']); 
            if ($block->max_floor < $data['floor_number']) {
                return response()->json(
                    ['messagge' => 
                       'El numero de piso es mayor a la maximo piso del bloque seleccionado'], 
                    400
                );
            }

            DB::transaction(
                function() use ($data) 
                {
                    $classroom = new Classroom();
                    $classroom->name = $data['classroom_name']; 
                    $classroom->capacity = $data['capacity'];
                    $classroom->floor = $data['floor_number']; 
                    $classroom->block_id = $data['block_id']; 
                    $classroom->classroom_type_id = $data['type_id']; 
    
                    $classroom->save();    
                }
            );
            
            return response()->json(
                ['message'=>'Ambiente registrado correctamente'], 
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message'=>'Ha ocurrido un error en el servidor', 
                    'error'=>$e->getMessage()
                ],
                500
            ); 
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
