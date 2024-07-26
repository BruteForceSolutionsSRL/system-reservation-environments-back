<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\{
    JsonResponse as Response,
    Request
};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Service\ServiceImplementation\{
    ReservationServiceImpl,
    TimeSlotServiceImpl,
    PersonServiceImpl
};

class ReservationController extends Controller
{
    private $reservationService;
    private $timeSlotService; 
    private $personService; 
    public function __construct()
    {
        $this->reservationService = new ReservationServiceImpl();
        $this->timeSlotService = new TimeSlotServiceImpl();
        $this->personService = new PersonServiceImpl();
    }

    /**
     * Retrieves all reservations.
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        try {
            return response()->json(
                $this->reservationService->getAllReservations(), 
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
     * Function to get all requests except pending requests
     * @param Request $request
     * @return Response
     */
    public function getAllRequestsExceptPending(Request $request): Response
    {
        try {
            return response()->json(
                $this->reservationService->getAllReservationsExceptPending(),
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo en error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Function to retrieve all pending request
     * @param Request $request
     * @return Response
     */
    public function getPendingRequests(Request $request): Response
    {
        try {
            return response()->json(
                $this->reservationService->getPendingRequest(), 
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
     * Function to retrieve acepted/pending request by teacher
     * @param int $teacher
     * @return Response
     * @param Request $request
     */
    public function listRequestsByTeacher(int $teacherId, Request $request): Response
    {
        try {
            $reservations = $this->reservationService->listRequestsByTeacher($teacherId);
            if (array_key_exists('message', $reservations)) {
                return response()->json($reservations, 404);
            }
            return response()->json(
                $this->reservationService->listRequestsByTeacher($teacherId), 
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
     * Function to retrieve all request by teacher
     * @param int $teacherId
     * @param Request $request
     * @return Response
     */
    public function listAllRequestsByTeacher(int $teacherId, Request $request): Response
    {
        try {
            $reservations = $this->reservationService
                ->listAllRequestsByTeacher($teacherId); 
            if (array_key_exists('message', $reservations)) {
                return response()->json($reservations, 404);
            }
            return response()->json(
                $reservations,
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
     * function to get all requests except pending requests by teacher
     * @param int $teacherId
     * @param Request $request
     * @return Response
     */
    public function getAllRequestsExceptPendingByTeacher(
        int $teacherId, 
        Request $request
    ): Response
    {
        try {
            return response()->json(
                $this->reservationService
                    ->getAllReservationsExceptPendingByTeacher($teacherId),
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo en error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }


    /**
     * Function to retrieve a reservation by its id
     * @param int $reservationId
     * @param Request $request
     * @return Response
     */
    public function show(int $reservationId, Request $request): Response
    {
        try {
            $reservation = $this->reservationService
                ->getReservation($reservationId); 
            if (empty($reservation)) {
                return response()->json([
                    'message' => 'La reserva a la que trata de acceder no existe.'
                ], 404);
            }
            return response()->json($reservation, 200);
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
     * Function to reject a reservation by its id
     * @param int $reservationId
     * @param Request $request
     * @return Response
     */
    public function rejectReservation(int $reservationId, Request $request): Response
    {
        try {
            $message = $this->reservationService->reject(
                $reservationId, 
                $request->input('message'),
                $request['session_id']
            ); 
            $pos = strpos($message, 'No existe'); 
            if ($pos !== false) 
                return response()->json(['message' => $message], 404);
            
            $pos = strpos($message, 'expirada');
            if ($pos !== false) 
                return response()->json(['message' => $message], 400);
            

            return response()->json(['message' => $message], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al rechazar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a accepted special request-booking
     * @param int $specialReservationId
     * @param Request $request
     * @return Response
     */
    public function specialCancel(int $specialReservationId, Request $request): Response
    {
        try {
            $message = $this->reservationService->specialCancel($specialReservationId); 
            if ($message == 'No existe una reserva con este ID') {
                return response()->json(['message' => $message], 404);
            }
            return response()->json(['message' => $message], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al cancelar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a pending/accepted request-booking
     * @param int $reservationId
     * @param Request $request
     * @return Response
     */
    public function cancelRequest(int $reservationId, Request $request): Response
    {
        try {
            $personId = $request['session_id'];
            $person = $this->personService->getUser($personId);

            $message = $this->reservationService->cancel(
                $reservationId,
                'Razon de la cancelacion es: El usuario: '.$person['fullname'].' cancelo la reserva'
            ); 
            
            $pos = strpos($message, 'No existe');
            if ($pos !== false) 
                return response()->json(['message' => $message], 404);
            
            $pos = strpos($message, 'expirada');
            if ($pos !== false) 
                return response()->json(['message' => $message], 400);
            

            return response()->json(['message' => $message], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al cancelar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save a new Classroom Booking Request
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        try {
            $validator = $this->validateReservationData($request);

            if ($validator->fails()) 
                return response()->json(
                    ['message' => implode('.', $validator->errors()->all())], 
                    400
                );

            $data = $validator->validated();

            $requestedHour = Carbon::parse($data['date'].' '.$this->timeSlotService
                ->getTimeSlot($data['time_slot_id'][0])['time'])->addHours(4); 

            $now = Carbon::now();
            if ($now >= $requestedHour)
                return response()->json(
                    ['message' => 'La hora elegida ya paso, no es posible realizar una reserva, intente seleccionar una hora mayor.'], 
                    404
                ); 

            $result = $this->reservationService->store($data);
            $pos = strpos($result, 'No existen');
            if ($pos !== false)
                return response()->json(['message' => $result], 400);
            $pos = strpos($result, 'rechazo'); 
            if ($pos !== false)
                return response()->json(['message' => $result], 201);
            $pos = strpos($result, 'aceptada');
            if ($pos !== false)
                return response()->json(['message' => $result], 202);

            return response()->json(
                ['message' =>$result], 
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
     * Validate Request data from a form
     * @param Request $request
     * @return mixed
     */
    private function validateReservationData(Request $request)
    {
        return Validator::make($request->all(), [
            'quantity' => 'required|integer|min:25|max:500',
            'date' => 'required|date',
            'reservation_reason_id' => 'required|int|exists:reservation_reasons,id',
            'teacher_subject_ids' => 'array',
            'teacher_subject_ids.*' => 'exists:teacher_subjects,id',
            'classroom_ids.*' => 'required|exists:classrooms,id',
            'time_slot_ids.*' => 'required|exists:time_slots,id',
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
            'block_id' => 'required|int|exists:blocks,id',
        ], [
            'quantity.required' => 'El número de estudiantes es obligatorio.',
            'quantity.integer' => 'El número de estudiantes debe ser un valor entero.',
            'quantity:min' => 'La cantidad debe ser un numero positivo mayor o igual a 25',
            'quantity:max' => 'La cantidad debe ser un numero positivo menor o igual a 500',
            
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser un formato válido.',
            
            'reservation_reason_id.required' => 'El motivo de la reserva es obligatorio.',
            'reservation_reason_id.string' => 'El motivo de la reserva debe ser un texto.',
            
            'teacher_subject_ids.required' => 'Se requiere al menos la seleccion de un grupo de la asignatura',
            'teacher_subject_ids.*.required' => 'Se requiere al menos una asignatura de profesor.',
            'teacher_subject_ids.*.exists' => 'Una de las asignaturas de profesor seleccionadas no es válida.',
            
            'classroom_ids.*.required' => 'Se requiere al menos una aula.',
            'classroom_ids.*.exists' => 'Una de las aulas seleccionadas no es válida.',
            
            'time_slot_ids.*.required' => 'Se requieren los periodos de tiempo.',
            'time_slot_ids.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido.',
            'time_slot_ids.required' => 'Se requieren dos periodos de tiempo.',
            'time_slot_ids.array' => 'Los periodos de tiempo deben ser un arreglo.',
            
            'block_id.required' => 'Debe seleccionar un bloque.',
            'block_id.int' => 'El id del bloque debe ser un entero',
            'block_id.exists' => 'El id del bloque seleccionado, no existe'
        ]);
    }

    /**
     * Endpoint to assign reservations if fulfill no overlapping with assigned reservations.
     * @param int $reservationId
     * @param Request $request
     * @return Response
     */
    public function assign(int $reservationId, Request $request): Response
    {
        try {
            $message = $this->reservationService->accept($reservationId, true); 
            $pos = strpos($message, 'No existe');
            if ($pos !== false) 
                return response()->json(['message' => $message], 404);
            
            $pos = strpos($message, 'expirada');
            if ($pos !== false) 
                return response()->json(['message' => $message], 400);
            
            $message = 'La solicitud de reserva fue aceptada correctamente, se envio una notificacion, a todos los docentes de dicha reserva';
            return response()->json(['message' => $message], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error en el servidor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint to retrieve if a reservation have conflicts
     * @param int $reservationId
     * @param Request $request
     * @return Response
     */
    public function getConflicts(int $reservationId, Request $request): Response
    {
        try {
            $result = $this->reservationService->getConflict($reservationId); 
            if (array_key_exists('message', $result)) {
                return response()->json($result, 404);
            }
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    /**
     * Function to get reservations accepted, pending and reject
     * @param int $classromId
     * @param Request $request
     * @return Response
     */
    public function getAllReservationsByClassroom(int $classromId, Request $request): Response
    {
        try {
            $reservations = $this->reservationService
                ->getAllReservationsByClassroom($classromId); 
            if (empty($reservations)) {
                response()->json(
                    ['message' => 'El ambiente no tiene reservaciones pendientes o aceptadas.'],
                    404
                );
            } 
            return response()->json($reservations, 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    /**
     * Endpoint to retrieve if a reservation have conflicts
     * @param Request $request
     * @return Response
     */
    public function getReports(Request $request):Response
    {
        try {
            $validator = $this->validateGetReportsData($request);

            if ($validator->fails()) 
                return response()->json(
                    ['message' => implode('.', $validator->errors()->all())], 
                    400
                );

            $data = $validator->validated();
            $report = $this->reservationService->getReports($data);
            if (empty($report['report'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron datos.',
                ], 404);
            }
            return response()->json(
                $report, 
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
     * Validate the body request for retrieve a report data
     * @param Request $request
     * @return mixed
     */
    private function validateGetReportsData(Request $request)
    {
        return Validator::make($request->all(), [
            'date_start' => '
                required|
                date',
            'date_end' => '
                required|
                date|
                after_or_equal:date_start',
            'block_id' => '
                nullable|
                integer|
                exists:blocks,id',
            'classroom_id' => '
                nullable|
                integer|
                exists:classrooms,id',
            'reservation_status_id' => '
                nullable|
                integer|
                exists:reservation_statuses,id',
            'university_subject_id' => '
                nullable|
                integer|
                exists:university_subjects,id',
            'person_id' => '
                nullable|
                integer|
                exists:people,id',
        ], [
            'date_start.required' => 'La fecha de inicio es obligatoria',
            'date_start.date' => 'La fecha de incio debe tener un formato válido',
            
            'date_end.required' => 'La fecha de fin es obligatoria',
            'date_end.date' => 'La fecha de fin debe tener un formato válido',
            'date_end.after_or_equal' => 'La fecha de fin debe ser mayor o igual a la fecha de inicio',          
            
            'block_id.integer' => 'El bloque debe ser un valor entero',
            'block_id.exists' => 'El bloque debe ser una selección válida',

            'classroom_id.integer' => 'El id del ambiente tiene que tener un formato valido',
            'classroom_id.exists' => 'El ambiente seleccionados no es válido',
        
            'reservation_status_id.integer' => 'El estado de la reserva debe ser un valor entero',
            'reservation_status_id.exists' => 'El estado de la reserva debe ser una selección válida',

            'university_subject_id.integer' => 'El ID de la asignatura universitaria debe ser un valor entero',
            'university_subject_id.exists' => 'La asignatura universitaria debe ser una selección válida',

            'person_id.integer' => 'El ID de la persona debe ser un valor entero',
            'person_id.exists' => 'La persona debe ser una selección válida',
        ]);
    }

    /**
     * Retrieve a list of all special reservation 
     * @return Response 
     */
    public function getActiveSpecialReservations(): Response 
    {
        try {
            return response()->json(
                $this->reservationService->getActiveSpecialReservations(), 
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
     * Endpoint function to accept a `special` reservation
     * @param Request $request
     * @return Response
     */
    public function storeSpecialRequest(Request $request): Response
    {
        try {
            $validator = $this->validateSpecialReservation($request);

            if ($validator->fails()) 
                return response()->json(
                    ['message' => implode('.', $validator->errors()->all())], 
                    400
                );

            $data = $validator->validated();
            return response()->json(
                ['message' => $this->reservationService->saveSpecialReservation($data)], 
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
     * Validate a single request for special reservation request
     * @param Request $request
     * @return mixed
     */
    private function validateSpecialReservation(Request $request) 
    {
        return Validator::make($request->all(), [
            'quantity' => 'required|integer|min:100',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'reason_id' => 'required|int|exists:reservation_reasons,id',
            'observation' => 'required|string',
            'classroom_ids' => 'array',
            'classroom_ids.*' => 'exists:classrooms,id',
            'time_slot_ids.*' => 'required|exists:time_slots,id',
            'time_slot_ids' => [
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
            'block_id' => 'array',
            'block_id.*' => 'nullable|int|exists:blocks,id',
        ], [
            'quantity.required' => 'El número de estudiantes es obligatorio.',
            'quantity.integer' => 'El número de estudiantes debe ser un valor entero.',
            'quantity:min' => 'La cantidad debe ser un numero positivo mayor o igual a 100',

            'date_start.required' => 'La fecha es obligatoria.',
            'date_start.date' => 'La fecha debe ser un formato válido.',
            'date_end.required' => 'La fecha es obligatoria.',
            'date_end.date' => 'La fecha debe ser un formato válido.',
            
            'reason_id.required' => 'El motivo de la reserva es obligatorio.',
            'reason_id.int' => 'El motivo de la reserva debe hacer referencia al motivo.',
            
            'observation.required' => 'El titulo u observacion no debe ser nula.',
            'observation.string' => 'El titulo y observacion debe ser una cadena de texto.',

            'classroom_ids.*.required' => 'Se requiere al menos una aula.',
            'classroom_ids.*.exists' => 'Una de las aulas seleccionadas no es válida.',
            
            'time_slot_ids.*.required' => 'Se requieren los periodos de tiempo.',
            'time_slot_ids.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido.',
            'time_slot_ids.required' => 'Se requieren dos periodos de tiempo.',
            'time_slot_ids.array' => 'Los periodos de tiempo deben ser un arreglo.',
        ]);
    }

    public function test(): Response
    {
        return response()->json($this->reservationService->test(), 200);
    }
}