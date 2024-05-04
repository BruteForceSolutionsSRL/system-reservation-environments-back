<?php
namespace App\Http\Controllers;

use Exception;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Service\ServiceImplementation\ReservationServiceImpl; 

class ReservationController extends Controller
{
    private $robotService;
    function __construct()
    {
        $this->robotService = new ReservationServiceImpl();
    }
    /**
     * index function retrieves all reservations.
     * @return Response
     */
    public function index(): Response
    {
        try {
            return response()->json($this->robotService->getAllReservations(), 
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
     * Function to retrieve all pending request
     * @return Response
     */
    public function getPendingRequests(): Response
    {
        try {
            return response()->json($this->robotService->getPendingRequest(), 200);
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
     */
    public function listRequestsByTeacher(int $teacherId): Response
    {
        try {
            $reservations = $this->robotService->listRequestsByTeacher($teacherId);
            if (array_key_exists('message', $reservations)) {
                return response()->json($reservations, 404);
            }
            return response()->json(
                $this->robotService->listRequestsByTeacher($teacherId), 
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
     * @return Response
     */
    public function listAllRequestsByTeacher(int $teacherId): Response
    {
        try {
            $reservations = $this->robotService->listRequestsByTeacher($teacherId); 
            if (array_key_exists('message', $reservations)) {
                return response()->json($reservations, 404);
            }
            return response()->json(
                $this->robotService->listRequestsByTeacher($teacherId), 
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
     * Function to retrieve a reservation by its id
     * @param int $reservationId
     * @return array
     */
    public function show(int $reservationId): array
    {
        try {
            $reservation = $this->robotService->getReservation($reservationId); 
            if ($reservation == []) {
                return response()->json([
                    'message' => 'La reserva no existe'
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
     * @return Response
     */
    public function rejectReservation(int $reservationId): Response
    {
        try {
            $message = $this->robotService->reject($reservationId); 
            if ($message == 'No existe una solicitud con este ID') {
                return response()->json(['message' => $message], 404);
            }
            return response()->json(['message' => $message], 200);
        } catch (Exception $err) {
            return response()->json([
                'message' => 'Ocurrio un error al rechazar la solicitud.',
                'error' => $err->getMessage()
            ], 500);
        }
    }
    /**
     * Explain:
     * Cancel a pending/accepted request-booking
     * @param int $reservationId
     * @return Response
     */
    public function cancelRequest(int $reservationId): Response
    {
        try {
            $message = $this->robotService->reject($reservationId); 
            if ($message == 'No existe una solicitud con este ID') {
                return response()->json(['message' => $message], 404);
            }
            return response()->json(['message' => $message], 200);
        } catch (Exception $err) {
            return response()->json([
                'message' => 'Ocurrio un error al cancelar la solicitud.',
                'error' => $err->getMessage()
            ], 500);
        }
    }
    /**
     * Explain:
     * Save a new Classroom Booking Request
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        try {
            $validator = $this->validateReservationData($request);

            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message .= $value . '\n';
                return response()->json(['message' => $message], 400);
            }

            $data = $validator->validated();
            return response()->json(['message' => $this->robotService->store($data)], 200);
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
     * Validate Request data from a form 
     * @param Request $request
     * @return mixed
     */
    private function validateReservationData(Request $request)
    {
        return Validator::make($request->all(), [
            'quantity' => 'required|integer|min:25|max:500',
            'date' => 'required|date',
            'reason_id' => 'required|int|exists:reservation_reasons,id',
            'group_id.*' => 'required|exists:teacher_subjects,id',
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
            'quantity.required' => 'El número de estudiantes es obligatorio.',
            'quantity.integer' => 'El número de estudiantes debe ser un valor entero.',
            'quantity:min' => 'La cantidad debe ser un numero positivo mayor o igual a 25',
            'quantity:max' => 'La cantidad debe ser un numero positivo menor o igual a 500',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser un formato válido.',
            'reason_id.required' => 'El motivo de la reserva es obligatorio.',
            'reason_id.string' => 'El motivo de la reserva debe ser un texto.',
            'group_id.required' => 'Se requiere al menos la seleccion de un grupo de la asignatura',
            'group_id.*.required' => 'Se requiere al menos una asignatura de profesor.',
            'group_id.*.exists' => 'Una de las asignaturas de profesor seleccionadas no es válida.',
            'classroom_id.*.required' => 'Se requiere al menos una aula.',
            'classroom_id.*.exists' => 'Una de las aulas seleccionadas no es válida.',
            'time_slot_id.*.required' => 'Se requieren los periodos de tiempo.',
            'time_slot_id.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido.',
            'time_slot_id.required' => 'Se requieren dos periodos de tiempo.',
            'time_slot_id.array' => 'Los periodos de tiempo deben ser un arreglo.',
        ]);
    }
    /**
     * Endpoint to assign reservations
     * if fulfill no overlapping with assigned
     * reservations.
     * @param int $reservationId
     * @return Response
     */
    public function assign(int $reservationId): Response
    {
        try {
            $message = $this->robotService->accept($reservationId); 
            if ($message == 'No existe una solicitud con este ID') {
                return response()->json(['message' => $message], 404);
            }
            return response()->json(['message' => $message], 200);
        } catch (Exception $err) {
            return response()->json([
                'message' => 'Ocurrio un error en el servidor.',
                'error' => $err->getMessage()
            ], 500);
        }
    }
    /**
     * Endpoint to retrieve if a reservation have conflicts
     * @param int $reservationId
     * @return Response
     */
    public function getConflicts(int $reservationId): Response
    {
        try {
            $result = $this->robotService->getConflict($reservationId); 
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
}
