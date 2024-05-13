<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Validator;

use Exception;

use Illuminate\Http\{
    JsonResponse as Response,
    Request,
};

use App\Models\Block;

use App\Service\ServiceImplementation\ClassroomServiceImpl as ClassroomService;

class ClassroomController extends Controller
{
    private $robotService;
    function __construct()
    {
        $this->robotService = new ClassroomService();
    }
    /**
     * Explain:
     * list function retrieves all classrooms.
     * @param none
     * @return Response
     */
    public function list(): Response
    {
        try {
            return response()->json($this->robotService->getAllClassrooms(), 
                200);
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

    /**
     * Explain:
     * To retrieve data available classrooms within block
     * @param int $blockId
     * @return Response
     */    
    public function availableClassroomsByBlock($blockId): Response
    {
        try {
            Block::findOrFail($blockId);
            return response()->Json($this->robotService->availableClassroomsByBlock($blockId));
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'El bloque especificado no existe.',
                    'error' => $e->getMessage()
                ],
                404
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

    /**
     * Explain:
     * Return all classrooms in a block
     * @param int $blockId
     * @return Response
     */ 
    public function classroomsByBlock($blockId): Response
    {
        
        try {
            Block::findOrFail($blockId);
            return response()->json($this->robotService->getClassroomsByBlock($blockId));
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'El bloque especificado no existe',
                    'error' => $e->getMessage()
                ], 
                404
            );
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
     * Explain:
     * Store a new classroom with the info below
     * @param Request
     * Request (body): 
     * {
     * "classroom_name": "str", 
     * "capacity": number, 
     * "type_id": int, 
     * "block_id": int
     * "floor_number": int
     * }
     * @return Response
     */
    public function store(Request $request): Response 
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
        
            return response()->json(['message'=> $this->robotService->store($data)],200);
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
     * Explain:
     * Validate all atributes whithin Classroom register
     * @param Request $request
     * @return mixed
     */
    private function validateClassroomData(Request $request) 
    {
        return Validator::make($request->all(), [
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
     * Explain:
     * Function to retrieve disponibility
     * status for all selected classrooms
     * @param Request $request
     * {
     * "date": "yyyy-mm-dd", 
     * "block_id": int,
     * "classroom_id": [int], 
     * "time_slot_id": [int]
     * }
     * @return Response
     */
    public function getClassroomByDisponibility(Request $request): Response 
    {
        try {

            $validator = $this->validateDisponibilityData($request);
            if ($validator->fails()) {
                $message = ''; 
                foreach ($validator->errors()->all() as $value) 
                    $message .= $value . '\n';
                return response()->json(['message' => $message], 400);
            }
            $data = $validator->validated();
            return response()->json($this->robotService->getClassroomByDisponibility($data), 200);
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

    /**
     * Explain:
     * Function to validate 
     * all input for a search of disponibility
     * @param Request $request
     * @return mixed
     */
    private function validateDisponibilityData(Request $request) 
    {
        return Validator::make($request->all(), [
            'date' => 'required|date',
            'block_id' => 'required|exists:blocks,id',
            'classroom_id.*' => [
                'required',
                Rule::exists('classrooms', 'id')->where(function ($query) use ($request) {
                    $query->where('block_id', $request->input('block_id'));
                }),
            ],
            'time_slot_id.*' => 'required|exists:time_slots,id',
            'time_slot_id' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) !== 2) {
                        $fail('Debe seleccionar exactamente dos periodos de tiempo.');
                    }else if ($value[1] <= $value[0]) {
                        $fail('El segundo periodo debe ser mayor que el primero.');
                    }
                }
            ],
        ], [
            'date.required' => 'La fecha es obligatoria.',
            'block_id.required' => 'El bloque no debe ir vacio',
            'block_id.exists' => 'El bloque seleccionado debe existir',
            'date.date' => 'La fecha debe ser un formato válido.',
            'classroom_id.*.required' => 'Se requiere al menos una aula.',
            'classroom_id.*.exists' => 'Una de las aulas seleccionadas no es válida.',
            'classroom:id.*.belongs' => 'Una de las aulas no pertenece al bloque seleccionado.',
            'time_slot_id.*.required' => 'Se requieren los periodos de tiempo.',
            'time_slot_id.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido.',
            'time_slot_id.required' => 'Se requieren dos periodos de tiempo.',
            'time_slot_id.array' => 'Los periodos de tiempo deben ser un arreglo.',
        ]);    
    }

    /**
     * Explain:
     * Function suggest a set of classrooms for a booking
     * @param Request $request
     * * {
     * "date": "yyyy-mm-dd", 
     * "quantity": number, 
     * "block_id": int,
     * "time_slot_id": [int]
     * }
     * @return Response
     */
    public function suggestClassrooms(Request $request): Response
    {
        try {
            $validator = $this->validateSuggestionData($request);

            if ($validator->fails()) {
                $message = ''; 
                foreach ($validator->errors()->all() as $value) 
                    $message .= $value . '\n';
                return response()->json(['message' => $message], 400);
            }

            $data = $validator->validated(); 

            return response()->json($this->robotService->suggestClassrooms($data),200);

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

    /**
     * Explain:
     * Function to validate 
     * all input for a search of suggestion
     * @param Request $request
     * @return mixed
     */
    private function validateSuggestionData(Request $request) 
    {
        return Validator::make($request->all(), [
            'date' => 'required|date',
            'quantity' => 'required|integer',
            'block_id' => 'required|exists:blocks,id',
            'time_slot_id.*' => 'required|exists:time_slots,id',
            'time_slot_id' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) !== 2) {
                        $fail('Debe seleccionar exactamente dos periodos de tiempo.');
                    }else if ($value[1] <= $value[0]) {
                        $fail('El segundo periodo debe ser mayor que el primero.');
                    }
                }
            ],
        ], [
            'date.required' => 'La fecha es obligatoria.',
            'quantity.required' => 'El número de estudiantes es obligatorio.',
            'quantity.integer' => 'El número de estudiantes debe ser un valor entero.',
            'block_id.required' => 'El bloque no debe ir vacio',
            'block_id.exists' => 'El bloque seleccionado debe existir',
            'date.date' => 'La fecha debe ser un formato válido.',
            'time_slot_id.*.required' => 'Se requieren los periodos de tiempo.',
            'time_slot_id.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido.',
            'time_slot_id.required' => 'Se requieren dos periodos de tiempo.',
            'time_slot_id.array' => 'Los periodos de tiempo deben ser un arreglo.',
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
