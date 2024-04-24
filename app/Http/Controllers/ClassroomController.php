<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Classroom; 
use App\Models\Block;

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
        try {
            $classrooms = Classroom::all()
            ->map(
                function ($classroom) 
                {
                    return [
                        'classroom_id' => $classroom->id, 
                        'classroom_name' => $classroom->name, 
                        'capacity' => $classroom->capacity, 
                        'floor_number' => $classroom->floor
                    ];
                }
            );
            return response()->json($classrooms, 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Error en el servidor', 
                    'error' => $e->getMessage()
                ],
                500
            );
        }
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
                ->map(
                    function ($classroom)
                    {
                        return [
                            'classroom_id' => $classroom->id,
                            'classroom_name' => $classroom->name,
                            'capacity' => $classroom->capacity,
                            'floor_number' => $classroom->floor,
                        ];
                    }
                );

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
            if ($block->maxfloor < $data['floor_number']) {
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
                    $classroom->floor = $data['type_id']; 
                    $classroom->block_id = $data['block_id']; 
                    $classroom->classroom_type_id = $data['floor_number']; 
    
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
     * Validate all atributes whithin Classroom register
     */
    private function validateClassroomData(Request $request) 
    {
        return \Validator::make($request->all(), [
            'classroom_name' => 'required|string|regex:/^[A-Z0-9\-\. ]+$/

            |unique:classrooms,name', 
            'capacity' => 'required|integer|min:25|max:500', 
            'type_id' => 'required|integer|exists:classroom_types,id', 
            'block_id' => 'required|integer|exists:blocks,id', 
            'floor_number' => 'required|integer|min:0'
        ], [
            'type_id.required' => 'El atributo \'tipo de ambiente\' no debe ser nulo o vacio', 
            'block_id.required' => 'El atributo \'bloque\' no debe ser nulo o vacio', 
            'classroom_name.required' => 'El atributo \'nombre\' no debe ser nulo o vacio', 
            'classroom_name.regex' => 'El nombre solamente puede tener caracteres alfanumericos y \'-\', \'.\' y \' \'',
            'capacity.required' => 'El atributo \'capacidad\' no debe ser nulo o vacio', 
            'floor_number.required' => 'El atributo \'piso\' no debe ser nulo o vacio', 
            'unique' => 'El nombre ya existe, intente con otro', 
            'capacity.min' => 'Debe seleccionar una capacidad mayor o igual a 25',
            'capacity.max' => 'Debe seleccionar una capacidad menor o igual a 500',
            'type_id.exists' => 'El \'tipo de ambiente\' debe ser una seleccion valida', 
            'block_id.exists' => 'El \'bloque\' debe ser una seleccion valida', 
            'floor_number.min' => 'El \'piso\' debe ser un numero positivo menor a la cantidad de pisos del bloque'
        ]);
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