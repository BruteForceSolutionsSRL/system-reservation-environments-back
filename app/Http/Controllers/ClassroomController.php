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

use App\Service\ServiceImplementation\{
    ClassroomServiceImpl as ClassroomService,
    BlockServiceImpl
};

class ClassroomController extends Controller
{
    private $classroomService;
    private $blockService;
    function __construct()
    {
        $this->classroomService = new ClassroomService();
        $this->blockService = new BlockServiceImpl();
    }

    /**
     * Explain:
     * list function retrieves all classrooms.
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        try {
            $classroomStatus = $request->query('status', 'ENABLED');
            return response()->json(
                $this->classroomService->getAllClassrooms($classroomStatus),
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

    /**
     * Explain:
     * list function retrieves all classrooms with statistis and format.
     * @param none
     * @return Response
     */
    public function getAllClassroomsWithStatistics(): Response
    {
        try {
            return  response()->json(
                $this->classroomService->getAllClassroomsWithStatistics(),
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

    /**
     * Retrieve a single classroom 
     * @param int $classroomId
     * @return Response
     */
    public function show(int $classroomId): Response
    {
        try {
            return response()->json(
                $this->classroomService->getClassroomByID($classroomId),
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

    /**
     * Update the data in a for specified classroom
     * @param int $classroomId
     * @param Request $request
     * @return Response
     */
    public function update(int $classroomId, Request $request): Response
    {
        try {
            $validator = $this->validateClassroomDataUpdate($request);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message = $message . $value . '.';
                return response()->json(
                    ['message' => $message],
                    400
                );
            }
            $data = $validator->validated();
            $data['classroom_id'] = $classroomId;

            $block = $this->blockService->getBlock($data['block_id']);
            if ($block['block_maxfloor'] < $data['floor_number']) {
                return response()->json(
                    ['messagge' =>
                    'El numero de piso es mayor a la maximo piso del bloque seleccionado'],
                    400
                );
            }

            return response()->json(
                ['message' => $this->classroomService->update($data)],
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

    /**
     * To retrieve data available classrooms within block
     * @param int $blockId
     * @return Response
     */
    public function availableClassroomsByBlock(int $blockId): Response
    {
        try {
            $block = $this->blockService->getBlock($blockId);
            if ($block == []) {
                return response()->json(
                    ['message' => 'El ID del bloque debe ser valido'],
                    400
                );
            }
            return response()->Json($this->classroomService->getDisponibleClassroomsByBlock($blockId));
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
     * Return all classrooms in a block
     * @param int $blockId
     * @return Response
     */
    public function classroomsByBlock(int $blockId): Response
    {
        try {
            $block = $this->blockService->getBlock($blockId);

            if ($block == []) {
                return response()->json(
                    ['message' => 'El ID del bloque debe ser valido'],
                    400
                );
            }

            return response()->json(
                $this->classroomService->getClassroomsByBlock($blockId),
                200
            );
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
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Store a new classroom with the info below
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        try {
            $validator = $this->validateClassroomData($request);

            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message = $message . $value . '.';
                return response()->json(
                    ['message' => $message],
                    400
                );
            }

            $data = $validator->validated();

            $block = $this->blockService->getBlock($data['block_id']);
            if ($block['block_maxfloor'] < $data['floor_number']) {
                return response()->json(
                    ['messagge' =>
                    'El numero de piso es mayor a la maximo piso del bloque seleccionado'],
                    400
                );
            }

            return response()->json(
                ['message' => $this->classroomService->store($data)],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Ha ocurrido un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Validate all atributes whithin Classroom register
     * @param Request $request
     * @return mixed
     */
    private function validateClassroomData(Request $request)
    {
        return Validator::make($request->all(), [
            'classroom_name' => '
                required|
                regex:/^[A-Z0-9\-\. ]+$/|
                unique:classrooms,name',
            'capacity' => '
                required|
                integer|
                min:25|
                max:500',
            'type_id' => '
                required|
                integer|
                exists:classroom_types,id',
            'block_id' => '
                required|
                integer|
                exists:blocks,id',
            'floor_number' => '
                required|
                integer|
                min:0'
        ], [
            'classroom_name.required' => 'El atributo \'nombre\' no debe ser nulo o vacio',
            'classroom_name.regex' => 'El nombre solamente puede tener caracteres alfanumericos y \'-\', \'.\', \' \'',
            'classroom_name.unique' => 'El nombre del ambiente ya se encuentra registrado',

            'capacity.required' => 'El atributo \'capacidad\' no debe ser nulo o vacio',
            'capacity.integer' => 'La \'capacidad\' debe ser un valor entero',
            'capacity.min' => 'Debe seleccionar una \'capacidad\' mayor o igual a 25',
            'capacity.max' => 'Debe seleccionar una \'capacidad\' menor o igual a 500',

            'type_id.required' => 'El atributo \'tipo de ambiente\' no debe ser nulo o vacio',
            'type_id.integer' => 'El \'tipo de ambiente\' debe ser un valor entero',
            'type_id.exists' => 'El \'tipo de ambiente\' debe ser una seleccion valida',

            'block_id.required' => 'El atributo \'bloque\' no debe ser nulo o vacio',
            'block_id.integer' => 'El \'bloque\' debe ser un valor entero',
            'block_id.exists' => 'El \'bloque\' debe ser una seleccion valida',

            'floor_number.required' => 'El atributo \'piso\' no debe ser nulo o vacio',
            'floor_number.integer' => 'El \'piso\' debe ser un valor entero',
            'floor_number.min' => 'El \'piso\' debe ser un numero positivo menor a la cantidad de pisos del bloque'
        ]);
    }

    /**
     * Validate all atributes whithin Classroom update
     * @param Request $request
     * @return mixed
     */
    private function validateClassroomDataUpdate(Request $request)
    {
        return Validator::make($request->all(), [
            'classroom_id' => '
                required|
                integer|
                exists:classrooms,id',
            'capacity' => '
                required|
                integer|
                min:25|
                max:500',
            'type_id' => '
                required|
                integer|
                exists:classroom_types,id',
            'block_id' => '
                required|
                integer|
                exists:blocks,id',
            'floor_number' => '
                required|
                integer|
                min:0',
            'status_id' => '
                required|
                integer|
                exists:classroom_statuses,id'
        ], [
            'classroom_id.required' => 'El atributo \'ambiente\' no debe ser nulo o vacio',
            'classroom_id.integer' => 'El \'ambiente\', debe ser un valor entero',
            'classroom_id.exists' => 'El \'ambiente\' no existe',

            'capacity.required' => 'El atributo \'capacidad\' no debe ser nulo o vacio',
            'capacity.integer' => 'La \'capacidad\' debe ser un valor entero',
            'capacity.min' => 'Debe seleccionar una \'capacidad\' mayor o igual a 25',
            'capacity.max' => 'Debe seleccionar una \'capacidad\' menor o igual a 500',

            'type_id.required' => 'El atributo \'tipo de ambiente\' no debe ser nulo o vacio',
            'type_id.integer' => 'El \'tipo de ambiente\' debe ser un valor entero',
            'type_id.exists' => 'El \'tipo de ambiente\' debe ser una seleccion valida',

            'block_id.required' => 'El atributo \'bloque\' no debe ser nulo o vacio',
            'block_id.integer' => 'El \'bloque\', debe ser un valor entero',
            'block_id.exists' => 'El \'bloque\' debe ser una seleccion valida',

            'floor_number.required' => 'El atributo \'piso\' no debe ser nulo o vacio',
            'floor_number.integer' => 'El \'piso\' debe ser un valor entero',
            'floor_number.min' => 'El \'piso\' debe ser un numero positivo menor a la cantidad de pisos del bloque',

            'status_id.required' => 'El \'estado\' debe ser una opcion valida',
            'status_id.integer' => 'El \'estado\' debe ser un valor entero',
            'status_id.exists' => 'La opcion de \'estado\' seleccionada no existe',
        ]);
    }


    /**
     * Function to retrieve disponibility status for all selected classrooms
     * @param Request $request
     * @return Response
     */
    public function getClassroomByDisponibility(Request $request): Response
    {
        try {

            $validator = $this->validateDisponibilityData($request);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message .= $value . '.';
                return response()->json(['message' => $message], 400);
            }
            $data = $validator->validated();
            return response()->json(
                $this->classroomService->getClassroomByDisponibility($data),
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

    /**
     * Function to validate all input for a search of disponibility
     * @param Request $request
     * @return mixed
     */
    private function validateDisponibilityData(Request $request)
    {
        return Validator::make($request->all(), [
            'date' => '
                required|
                date',
            'block_id' => '
                required|
                integer|
                exists:blocks,id',
            'classroom_id' => 'array',
            'classroom_id.*' => [
                'required',
                'integer',
                Rule::exists('classrooms', 'id')->where(function ($query) use ($request) {
                    $query->where('block_id', $request->input('block_id'));
                }),
            ],
            'time_slot_id' => 'array',
            'time_slot_id.*' => '
                required|
                integer|
                exists:time_slots,id',
            'time_slot_id' => [
                function ($attribute, $value, $fail) {
                    if (count($value) !== 2) {
                        $fail('Debe seleccionar exactamente dos periodos de tiempo.');
                    } else if ($value[1] <= $value[0]) {
                        $fail('El segundo periodo debe ser mayor que el primero.');
                    }
                }
            ],
        ], [
            'date.required' => 'El atributo \'fecha\' no debe ser nulo y vacio',
            'date.date' => 'La \'fecha\' debe tener un formato válido',

            'block_id.required' => 'El atributo \'bloque\' no debe ser nulo o vacio',
            'block_id.integer' => 'El \'bloque\', debe ser un valor entero',
            'block_id.exists' => 'El \'bloque\' debe ser una seleccion valida',
            
            'classroom_id.array' => 'Los identificadores de los ambientes deben estar en un arreglo',
            'classroom_id.*.required' => 'Se requiere al menos un ambiente',
            'classroom_id.*.integer' => 'Todos los ambientes del arreglo deben ser enteros',
            'classroom_id.*.exists' => 'Uno de los ambientes seleccionados no es válido',
            'classroom_id.*.belongs' => 'Uno de los ambientes no pertenece al bloque seleccionado',

            'time_slot_id.array' => 'Los periodos de tiempo deben estar en un arreglo',
            'time_slot_id.*.required' => 'Se requieren los periodos de tiempo',
            'time_slot_id.integer' => 'Todos los periodos dentro del arreglo deben ser enteros',
            'time_slot_id.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido',         
        ]);
    }

    /**
     * Function suggest a set of classrooms for a booking
     * @param Request $request
     * @return Response
     */
    public function suggestClassrooms(Request $request): Response
    {
        try {
            $validator = $this->validateSuggestionData($request);

            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message .= $value . '.';
                return response()->json(['message' => $message], 400);
            }

            $data = $validator->validated();

            return response()->json($this->classroomService->suggestClassrooms($data), 200);
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
     * Function to validate all input for a search of suggestion
     * @param Request $request
     * @return mixed
     */
    private function validateSuggestionData(Request $request)
    {
        return Validator::make($request->all(), [
            'date' => '
                required|
                date',
            'quantity' => '
                required|
                integer',
            'block_id' => '
                required|
                integer|
                exists:blocks,id',
            'time_slot_id' => 'array',
            'time_slot_id.*' => '
                required|
                integer|
                exists:time_slots,id',
            'time_slot_id' => [
                function ($attribute, $value, $fail) {
                    if (count($value) !== 2) {
                        $fail('Debe seleccionar exactamente dos periodos de tiempo.');
                    } else if ($value[1] <= $value[0]) {
                        $fail('El segundo periodo debe ser mayor que el primero.');
                    }
                }
            ],
        ], [
            'date.required' => 'El atributo \'fecha\' no debe ser nulo y vacio',
            'date.date' => 'La \'fecha\' debe tener un formato válido',

            'quantity.required' => 'El número de estudiantes es obligatorio.',
            'quantity.integer' => 'El número de estudiantes debe ser un valor entero.',

            'block_id.required' => 'El atributo \'bloque\' no debe ser nulo o vacio',
            'block_id.integer' => 'El \'bloque\', debe ser un valor entero',
            'block_id.exists' => 'El \'bloque\' debe ser una seleccion valida',
            
            'time_slot_id.array' => 'Los periodos de tiempo deben estar en un arreglo',
            'time_slot_id.*.required' => 'Se requieren los periodos de tiempo',
            'time_slot_id.integer' => 'Todos los periodos dentro del arreglo deben ser enteros',
            'time_slot_id.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido',
        ]);
    }

    /**
     * Function to retrieve a classroom not deleted and with a date earlier than the provided date
     * @param  Request $request
     * @return Response
     */
    public function retriveLastClassroom(Request $request): Response
    {
        try {
            $validator = $this->validateRetriveLastClassroomData($request);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message .= $value . '.';
                return response()->json(
                    ['message' => $message],
                    400
                );
            }

            $data = $validator->validated();

            $classroom = $this->classroomService->retriveLastClassroom($data);
            if (count($classroom) <= 0) {
                return response()->json(
                    ['message' => 'Aula no encontrada'],
                    404
                );
            }
            return response()->json($classroom, 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'error interno del servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
    /**
     * Function to validate all input for a retrive a last classroom
     * @param Request $request
     * @return mixed
     */
    private function validateRetriveLastClassroomData(Request $request)
    {
        return Validator::make($request->all(), [
            'date' => '
                required|
                date',
            'classroom_id' => '
                required|
                integer|
                exists:classrooms,id',
        ], [
            'date.required' => 'La fecha es obligatoria',
            'date.date' => 'La fecha debe ser un formato válido',

            'classroom_id.required' => 'El atributo \'ambiente\' no debe ser nulo o vacio',
            'classroom_id.integer' => 'El \'ambiente\', debe ser un valor entero',
            'classroom_id.exists' => 'El \'ambiente\' no existe',
        ]);
    }

    /**
     * Function for delete classroom by ID
     * @param int $classroomId
     * @return Response
     */
    public function destroy(int $classroomId): Response
    {
        try {
            $isDeleted = $this->classroomService->isDeletedClassroom($classroomId);
            if ($isDeleted) {
                return response()->json(
                    ['message' => 'El ambiente ya fue eliminado o no existe'],
                    404
                );
            }
            return response()->json($this->classroomService->deleteByClassroomId($classroomId));
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
     * Function to retrive statistics through start and end dates, along with classroom ID
     * @param Request $request
     * @return Response
     */
    function getClassroomStats(Request $request): Response
    {
        try {
            $validator = $this->validateGetClassroomStatsData($request);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message .= $value . '.';
                return response()->json(
                    ['message' => $message],
                    400
                );
            }
            $data = $validator->validated();
            $classroomStats = $this->classroomService->getClassroomStats($data);
            return response()->json($classroomStats);
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
     * Function to validate all input for retrieving statistics for a classroom
     * @param Request $request
     * @return mixed
     */
    private function validateGetClassroomStatsData(Request $request)
    {
        return Validator::make($request->all(), [
            'classroom_id' => '
                required|
                integer|
                exists:classrooms,id',
            'date_start' => '
                required|
                date',
            'date_end' => '
                required|
                date|
                after_or_equal:date_start'
        ], [
            'classroom_id.required' => 'El atributo \'classroom_id\' no debe ser nulo o vacio',
            'classroom_id.integer' => 'El atributo \'classroom_id\', debe ser un valor entero',
            'classroom_id.exists' => 'El atributo \'classroom_id\' no existe',

            'date_start.required' => 'La fecha de inicio es obligatoria',
            'date_start.date' => 'La fecha de incio debe tener un formato válido',
            
            'date_end.required' => 'La fecha de fin es obligatoria',
            'date_end.date' => 'La fecha de fin debe tener un formato válido',
            'date_end.after_or_equal' => 'La fecha de fin debe ser mayor o igual a la fecha de inicio'          
        ]);
    }
}   
