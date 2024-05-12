<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Classroom;
use App\Models\Block;
use App\Models\ReservationStatus;
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

    public function getClassroomByDisponibility(Request $request) 
    {
        try {
            $validator = $this->validateDisponibilityData($request);
            if ($validator->fails()) {
                $message = ''; 
                foreach ($validator->errors()->all() as $value) 
                    $message .= $value . '\n';
                return response()->json(['message' => $message], 400);
            }
            $classroomList = [];
            $data = $validator->validated();
            
            $initialTime = -1; 
            $endTime = -1; 
            foreach ($data['time_slot_id'] as $timeSlot) {
                if ($initialTime == -1) $initialTime = $timeSlot; 
                else $endTime = $timeSlot; 
            }
            if ($initialTime > $endTime) {
                $temp = $endTime; 
                $endTime = $initialTime; 
                $initialTime = $temp;
            }

            foreach ($data['classroom_id'] as $classroomId) {
                $classroom = Classroom::findOrFail($classroomId); 
                if ($classroom->block->id != $data['block_id']) {
                    return response()->json(
                        ['message' => 'Los ambientes seleccionados, no pertenecen al bloque'],
                        404
                    );
                }
                $acceptedStatus = ReservationStatus::where('status', 'ACCEPTED')
                                ->get()
                                ->pop()
                                ->id;
                $pendingStatus = ReservationStatus::where('status', 'PENDING')
                                ->get()
                                ->pop()
                                ->id;
    
                $element = array(); 
                $element['classroom_name'] = $classroom->name;

                $robot = new ReservationController();
                $reservations = $robot->getActiveReservationsWithDateStatusAndClassroom(
                    [$acceptedStatus, $pendingStatus], 
                    $data['date'], 
                    $classroomId
                );
                $reservations = $robot->cutReservationSetByTimeSlot(
                    $reservations, 
                    $initialTime, 
                    $endTime
                );

                for ($timeSlotId = $initialTime; $timeSlotId <= $endTime; $timeSlotId++) {
                    $timeSlot = TimeSlot::find($timeSlotId); 
                    $index = (string)($timeSlot->time);
                    $element[$index] = [
                        'valor' => 0, 
                        'message' => 'Disponible'
                    ];
                }

                foreach ($reservations as $reservation) {
                    $a = -1; 
                    $b = -1;

                    foreach ($reservation->timeSlots as $timeSlot) {
                        if ($a == -1) $a = $timeSlot->id; 
                        else $b = $timeSlot->id; 
                    }
                    if ($a > $b) {
                        $temp = $b; 
                        $b = $a; 
                        $a = $temp; 
                    }
                    $isAccepted = $reservation->reservation_status_id == $acceptedStatus; 
        
                    for ($timeSlotId = max($a, $initialTime); $timeSlotId <= min($endTime, $b); $timeSlotId++) {
                        $timeSlot = TimeSlot::find($timeSlotId);
                        $index = (string)($timeSlot->time);
                        $actualValue = $element[$index]['valor']; 

                        if ($actualValue == 2) continue; // ASSIGNED
                        if ($isAccepted) {
                            $element[$index]['valor'] = 1; 
                            $element[$index]['message'] = 'Ocupado';     
                        } else {
                            $element[$index]['valor'] = 2; 
                            $element[$index]['message'] = 'En revision';     
                        }
                    }
                }
                array_push($classroomList, $element);
            }
            return response()->json($classroomList, 200);
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
    private function validateDisponibilityData(Request $request) 
    {
        return \Validator::make($request->all(), [
            'date' => 'required|date',
            'block_id' => 'required|exists:blocks,id',
            'classroom_id.*' => 'required|exists:classrooms,id',
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
            'time_slot_id.*.required' => 'Se requieren los periodos de tiempo.',
            'time_slot_id.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido.',
            'time_slot_id.required' => 'Se requieren dos periodos de tiempo.',
            'time_slot_id.array' => 'Los periodos de tiempo deben ser un arreglo.',
        ]);    
    }

    public function suggestClassrooms(Request $request) 
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

            $classroomSet = Classroom::where('block_id', $data['block_id'])
                            ->get();
            $classroomSets = [];
            $max_floor = Block::find($data['block_id'])->max_floor;

            for ($i = 0; $i<=$max_floor; $i++) {
                $classroomSets[$i] = [
                    "quantity" => 0, 
                    "list" => array()
                ];
            }
            $acceptedStatus = ReservationStatus::where('status', 'ACCEPTED')
            ->get()
            ->pop()
            ->id;
            $robot = new ReservationController(); 

            foreach ($classroomSet as $classroom) {
                $reservations = $robot->getActiveReservationsWithDateStatusAndClassroom(
                    [$acceptedStatus], 
                    $data['date'], 
                    $classroom->id
                );
                $reservations = $robot->cutReservationSetByTimeSlot(
                    $reservations, 
                    $data['time_slot_id'][0], 
                    $data['time_slot_id'][1]
                );
                if (count($reservations) != 0) continue;
                $classroomSets[$classroom->floor]['quantity'] += $classroom->capacity; 
                array_push($classroomSets[$classroom->floor]['list'], $classroom);
            }

            $MAX_LEN = 1e4+10;
            $dp = array_fill(0, $MAX_LEN+1, $max_floor+100); 
            $pointerDp = array_fill(0, $MAX_LEN+1, -1); 
            $dp[0] = 0; 
            for ($i = 0; $i<=$max_floor; $i++) 
            for ($j = $MAX_LEN; $j>-1; $j--) 
            if ($dp[$j] < $max_floor+100) {
                $index = $j+$classroomSets[$i]['quantity']; 
                if ($index > $MAX_LEN) break; 
                $len = $dp[$j]+count($classroomSets[$i]['list']);

                if ($dp[$index] > $len) {
                    $dp[$index] = $len; 
                    $pointerDp[$index] = $i;
                }
            }

            $bestSuggest = $dp[$data['quantity']];

            for ($i = $data['quantity']; $i <= min($data['quantity']+300, 1e5); $i++) 
            if (($bestSuggest == -1) || ($dp[$bestSuggest] > $dp[$i])) 
                $bestSuggest = $i;

            if ($pointerDp[$bestSuggest] == -1) {
                return response()->json(
                    ['message' => 'No existe una sugerencia apropiada'],
                    400
                );
            }

            $classrooms = array();
            $piv = $bestSuggest; 
            while ($piv > 0) {
                $itemId = $pointerDp[$piv];
                foreach ($classroomSets[$itemId]['list'] as $classroom) 
                    array_push($classrooms, $classroom);
                $piv = $piv-$classroomSets[$itemId]['quantity']; 
            }

            $dp = array_fill(0, $MAX_LEN+1, -1); 
            $pointerDp = array_fill(0, $MAX_LEN, -1);
            $dp[0] = 0; 
            foreach ($classrooms as $classroom) 
            for ($j = $MAX_LEN; $j>-1; $j--) 
            if ($dp[$j] != -1) {
                $index = $j+($classroom->capacity); 
                if ($index > $MAX_LEN) continue;
                $len = $dp[$j]+1; 
                if (($dp[$index]==-1) || ($dp[$index] > $len)) {
                    $dp[$index] = $len; 
                    $pointerDp[$index] = $classroom->id;
                }
            }

            $bestSuggest = $data['quantity'];
            for ($i = $data['quantity']; $i <= $MAX_LEN; $i++) 
            if ($dp[$i] != -1)
            if (($dp[$bestSuggest] == -1) || ($dp[$bestSuggest] > $dp[$i])) 
                $bestSuggest = $i;

            $res = array();
            $piv = $bestSuggest; 
            while ($piv != 0) {
                $classroom = Classroom::find($pointerDp[$piv]);
                //return response()->json($pointerDp[$piv], 200);

                array_push($res, $this->formatClassroomResponse($classroom)); 
                $piv -= $classroom->capacity;
            }

            return response()->json(
                $res,
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
    private function validateSuggestionData(Request $request) 
    {
        return \Validator::make($request->all(), [
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
