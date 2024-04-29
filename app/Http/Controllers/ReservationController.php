<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Reservation;
use App\Models\ReservationStatus;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * index function retrieves all reservations.
     */
    public function index()
    {
        $reservations = Reservation::with([
            'reservationStatus:id,status',
            'reservationReason:id,reason',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,person_id,university_subject_id',
            'teacherSubjects.person:id,name,last_name',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,block_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->orderBy('date')->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return ReservationController::formatOutput($reservation);
        });

        return response()->json($formattedReservations, 200);
    }

    public function getPendingRequests()
    {
        $reservations = Reservation::with([
            'reservationStatus:id,status',
            'reservationReason:id,reason',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,person_id,university_subject_id',
            'teacherSubjects.person:id,name,last_name',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,block_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->where('date', '>=', Carbon::now()->format('Y-m-d'))
            ->where('reservation_status_id', '=', '3')
            ->orderBy('date')->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return ReservationController::formatOutput($reservation);
        });

        return response()->json($formattedReservations, 200);
    }

    public function listRequestsByTeacher($teacherId)
    {
        $reservations = Reservation::with([
            'reservationStatus:id,status',
            'reservationReason:id,reason',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,person_id,university_subject_id',
            'teacherSubjects.person:id,name,last_name',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,block_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->where('date', '>=', Carbon::now()->format('Y-m-d'))
            ->whereIn('reservation_status_id', [1, 3])
            ->whereHas('teacherSubjects', function ($query) use ($teacherId) {
                $query->where('person_id', $teacherId);
            })->orderBy('date')->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return ReservationController::formatOutput($reservation);
        });

        return response()->json($formattedReservations, 200);
    }

    public function listAllRequestsByTeacher($teacherId)
    {
        $reservations = Reservation::with([
            'reservationStatus:id,status',
            'reservationReason:id,reason',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,person_id,university_subject_id',
            'teacherSubjects.person:id,name,last_name',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,block_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->whereHas('teacherSubjects', function ($query) use ($teacherId) {
                $query->where('person_id', $teacherId);
            })->orderBy('date')->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return ReservationController::formatOutput($reservation);
        });

        return response()->json($formattedReservations, 200);
    }

    private function formatOutput(Reservation $reservation)
    {
        $reservationReason = $reservation->reservationReason;
        $reservationStatus = $reservation->reservationStatus;
        $classrooms = $reservation->classrooms;
        $teacherSubjects = $reservation->teacherSubjects;
        $timeSlots = $reservation->timeSlots;
        $priority = 0;

        if (Carbon::now()->diffInDays(Carbon::parse($reservation->date)) <= 5) {
            $priority = 1;
        }

        return [
            'reservation_id' => $reservation->id,
            'subject_name' => $teacherSubjects->first()->universitySubject->name,
            'quantity' => $reservation->number_of_students,
            'reservation_date' => $reservation->date,
            'timeSlot' => $timeSlots->map(function ($timeSlot) {
                return $timeSlot->time;
            }),
            'groups' => $teacherSubjects->map(function ($teacherSubject) {
                $person = $teacherSubject->person;

                return [
                    'teacher_name' => $person->name . ' ' . $person->last_name,
                    'group_number' => $teacherSubject->group_number,
                ];
            }),
            'classrooms' => $classrooms->map(function ($classroom) {
                return [
                    'classroom_name' => $classroom->name,
                    'capacity' => $classroom->capacity,
                ];
            }),
            'reason_name' => $reservationReason->reason,
            'priority' => $priority,
            'reservation_status' => $reservationStatus->status,
        ];
    }

    public function show($reservationId)
    {
        $reservation = Reservation::with([
            'reservationStatus:id,status',
            'reservationReason:id,reason',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,person_id,university_subject_id',
            'teacherSubjects.person:id,name,last_name',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,block_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->findOrFail($reservationId);

        if ($reservation == null) {
            return response()->json(['error'
                    => 'No existe una solicitud con este ID'], 404);
        }

        return ReservationController::formatOutput($reservation);
    }

    public function rejectReservation($reservationId)
    {
        try {
            $reservation = Reservation::findOrFail($reservationId);

            if ($reservation == null) {
                return response()->json(['error'
                        => 'No existe una solicitud con este ID'], 404);
            }

            if ($reservation->reservation_status_id == 2) {
                return response()->json(['error'
                        => 'Esta solicitud ya fue rechazada'], 200);
            }

            if ($reservation->reservation_statud_id != 3) {
                return response()->json(['error'
                        => 'Esta solicitud ya fue atendida'], 200);
            }

            $reservation->reservation_status_id = 2;
            $reservation->save();

            return response()->json(['mensaje'
            => 'La solicitud de reserva fue rechazada.'], 200);
        } catch (Exception $err) {
            return response()->json([
                'mensaje' => 'Ocurrio un error al rechazar la solicitud.',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function cancelRequest($reservationId)
    {
        try {
            $reservation = Reservation::findOrFail($reservationId);

            if ($reservation == null) {
                return response()->json(['error'
                        => 'No existe una solicitud con este ID'], 404);
            }

            if ($reservation->reservation_status_id == 4) {
                return response()->json(['error'
                        => 'Esta solicitud ya fue cancelada'], 200);
            }

            $reservation->reservation_status_id = 4;
            $reservation->save();

            return response()->json(['mensaje'
            => 'La solicitud de reserva fue cancelada.'], 200);
        } catch (Exception $err) {
            return response()->json([
                'mensaje' => 'Ocurrio un error al cancelar la solicitud.',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
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
            $pendingStatus = ReservationStatus::where('status', 'PENDING')
                                ->get()
                                ->pop();
            $acceptedStatus = ReservationStatus::where('status', 'ACCEPTED')
                                ->get()
                                ->pop();
            $rejectedStatus = ReservationStatus::where('status', 'REJECTED')
                                ->get()
                                ->pop();

            if (!array_key_exists('repeat', $data)) {
                $data['repeat'] = 0;
            }
            // Creacion de la reserva.
            DB::beginTransaction();
            $reservation = new Reservation();
            $reservation->number_of_students = $data['quantity'];
            $reservation->repeat = $data['repeat'];
            $reservation->date = $data['date'];
            $reservation->reservation_reason_id = $data['reason_id'];
            $reservation->reservation_status_id = $pendingStatus->id;
            $reservation->save();

            // Tabla intermediaria 'reservation_teacher_subject'.
            $reservation->teacherSubjects()->attach(
                $data['group_id'],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            //Tabla intermediaria 'classroom_reservation'.
            $reservation->classrooms()->attach(
                $data['classroom_id'],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            //Tabla intermediaria 'reservation_time_slot'.
            $reservation->timeSlots()->attach(
                $data['time_slot_id'],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            DB::commit();

            if ($this->checkAvailibility($reservation)) {
                if ($this->alertReservation($reservation)['ok'] != 0) {
                    return response()->json(
                        ['message' => 'Tu solicitud debe ser revisada por un administrador, se enviara una notificacion para mas detalles'],
                        200
                    );
                }
                $reservation->reservation_status_id = $acceptedStatus->id;
                $reservation->save();
                return response()->json(
                    ['message' => 'Tu solicitud de reserva fue aceptada'],
                    200
                );
            } else {
                $reservation->reservation_status_id = $rejectedStatus->id;
                $reservation->save();
                return response()->json(
                    ['message' => 'La reserva fue rechazada, existe uno o mas ambientes ocupados'],
                    200
                );
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    private function validateReservationData(Request $request)
    {
        return Validator::make($request->all(), [
            'quantity' => 'required|integer',
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
     * @param int, reservationId
     * @return \Response
     */
    public function assign($reservationId)
    {
        try {
            $reservation = Reservation::find($reservationId);
            if ($reservation==null) {
                return response()
                        ->json(
                            ['message' => 'La solicitud de reserva no existe'],
                            404
                        );
            }

            $reservationStatus = $reservation->reservationStatus;
            if ($reservationStatus->status!='PENDING') {
                return response()->json(
                        ['message' => 'Esta solicitud ya fue atendida'],
                        200
                    );
            }

            if (!$this->checkAvailibility($reservation)) {
                return response()->json(
                    ['message' => 'La solicitud no puede aceptarse, existen ambientes ocupados'],
                    200
                );
            }

            $acceptedStatus = ReservationStatus::where('status', 'ACCEPTED')
                            ->get()
                            ->pop()
                            ->id;
            $rejectedStatus = ReservationStatus::where('status', 'REJECTED')
                            ->get()
                            ->pop()
                            ->id;
            $pendingStatus = ReservationStatus::where('status', 'PENDING')
                            ->get()
                            ->pop()
                            ->id;

            $reservation->reservation_status_id = $acceptedStatus;
            $reservation->save();

            DB::beginTransaction();
            $date = $reservation->date;
            $initialTime = -1;
            $endTime = -1;
            foreach ($reservation->timeSlots as $timeSlot) {
                if ($initialTime == -1) $initialTime = $timeSlot->id;
                else $endTime = $timeSlot->id;
            }
            if ($initialTime > $endTime) {
                $temp = $endTime;
                $endTime = $initialTime;
                $initialTime = $temp;
            }
            foreach ($reservation->classrooms as $classroom) {
                $reservationSet = $this->getActiveReservationsWithDateStatusAndClassroom(
                    [$pendingStatus],
                    $date,
                    $classroom->id
                );
                $reservationSet = $this->cutReservationSetByTimeSlot(
                    $reservationSet,
                    $initialTime,
                    $endTime
                );
                foreach ($reservationSet as $reservationIterable) {
                    $reservationIterable->reservation_status_id = $rejectedStatus;
                    $reservationIterable->save();
                }
            }
            DB::commit();

            return response()->json(
                ['message' => 'La reserva fue aceptada correctamente'],
                200
            );
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error'=>$e->getMessage()
                ],
                500
            );
        }
    }
    /**
     * Function to check availability for all classrooms to do a
     * reservation in a step
     * @param Reservation
     * @return boolean
     */
    private function checkAvailibility($reservation)
    {
        $classrooms = $reservation->classrooms;
        $timeSlots = $reservation->timeSlots;
        $init = -1;
        $end = -1;
        foreach ($timeSlots as $iterable) {
            if ($init==-1) $init = $iterable->id;
            else $end = $iterable->id;
        }
        if ($init>$end) {
            $temp = $init;
            $init = $end;
            $end = $temp;
        }
        $date = $reservation->date;
        $acceptedStatusId = ReservationStatus::where('status', 'ACCEPTED')
                ->get()
                ->pop()
                ->id;
        foreach ($classrooms as $classroom) {
            $reservations = $this->getActiveReservationsWithDateStatusAndClassroom(
                [$acceptedStatusId],
                $date,
                $classroom->id
            );
            $reservations = $this->cutReservationSetByTimeSlot($reservations, $init, $end);
            if (count($reservations) != 0)
                return false;
        }
        return true;
    }
    /**
     * Cut and retrieve a subset of reservations
     * @param array
     * @param int
     * @param int
     * @return array
     */
    public function cutReservationSetByTimeSlot(
        $reservations,
        $initialTimeId,
        $endTimeId
    )
    {
        $refinedReservationSet = [];
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
            if (!($b <= $initialTimeId || $a >= $endTimeId)) {
                array_push($refinedReservationSet, $reservation);
            }
        }
        return $refinedReservationSet;
    }

    /**
     * Endpoint to retrieve if a reservation have conflicts
     * @param integer - reservationId
     * @return \Response
     */
    public function getConflicts($reservationId)
    {
        try {
            $reservation = Reservation::find($reservationId);
            if ($reservation == null) {
                return response()->json(
                    ['message' => 'La reserva no existe'],
                    404
                );
            }
            $result = $this->alertReservation($reservation);
            unset($result['ok']);
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
     * Check if a reservation in pending status have conflicts or is really `weird`
     * @param Reservation
     * @return array
     */
    public function alertReservation($reservation)
    {
        $result = [
            'quantity' => '',
            'classroom' => [
                'message' => '',
                'list' => array()
            ],
            'teacher' => [
                'message' => '',
                'list' => array()
            ],
            'ok' => 0
        ];
        $totalCapacity = $this->getTotalCapacity($reservation->classrooms);
        if ($totalCapacity < $reservation->number_of_students) {
            $result['quantity'] .= 'la cantidad de estudiantes excede la capacidad de estudiantes.\n';
            $result['ok'] = 1;
        }

        $usagePercent = $reservation->number_of_students / $totalCapacity * 100;
        if ($usagePercent < 50.0) {
            $message = 'la capacidad de los ambientes solicitados es muy elevada para la capacidad de ambientes solicitados.\n';
            $result['quantity'] .= $message;
            $result['ok'] = 1;
        }

        $block = $reservation->classrooms->pop()->block;
        $dp = array_fill(0, $block->max_floor+1, 0);
        $usedFloors = 0;
        foreach ($reservation->classrooms as $classroom) {
            $floor = $classroom->floor;
            if ($dp[$floor] == 0) {
                $dp[$floor] = 1;
                $usedFloors++;
            }
        }
        if ($usedFloors > 2) {
            $result['ok'] = 1;
            $message = 'los ambientes solicitados, se encuentran en mas de 2 pisos diferentes\n';
            $result['classroom']['message'] .= $message;
        }

        // question: if all classroooms are unique in pending requests:
        $statuses = [
            ReservationStatus::where('status', 'PENDING')
                    ->get()
                    ->pop()
                    ->id
        ];

        $date = $reservation->date;
        foreach ($reservation->classrooms as $classroom) {
            $classroomId = $classroom->id;

            $reservationSet = $this->getActiveReservationsWithDateStatusAndClassroom(
                $statuses,
                $date,
                $classroomId
            );
            if (count($reservationSet) > 1) {
                $result['ok'] = 1;
                array_push($result['classroom']['list'], $classroom->name);
            }
        }

        return $result;
    }
    public function getTotalCapacity($classrooms)
    {
        $total = 0;
        foreach ($classrooms as $classroom)
            $total += $classroom->capacity;
        return $total;
    }

    /**
     * Function to retrieve a list of all active reservations
     * @param array, statuses of reservations
     * @param string, date in format: 'Y-m-d'
     * @param int, classroom id
     */
    public function getActiveReservationsWithDateStatusAndClassroom(
        $statuses,
        $date,
        $classroomId
    )
    {
        $now = date('Y-m-d');
        $reservationSet = Reservation::
            whereHas(
                'classrooms',
                function ($query) use ($classroomId)
                {
                    $query -> where ('classroom_id', $classroomId);
                }
            )->where(
                function ($query) use ($statuses)
                {
                    foreach ($statuses as $status)
                        $query->orWhere('reservation_status_id', $status);
                }
            )->where(
                function ($query) use ($date, $now)
                {
                    $query->where('date', '>=', $now);
                    $query->where('date', $date);
                    $query->orWhere('repeat', '>', 0);
                }
            )->get();

        $result = [];
        foreach ($reservationSet as $reservation) {
            if ($reservation->repeat > 0) {
                $initialDate = new DateTime($date);
                $goalDate = new DateTime($reservation->date);
                $repeat = $reservation->repeat;

                $difference = $initialDate->diff($goalDate)->days;
                if ($difference % $repeat == 0)
                    array_push($result, $reservation);
            } else
                array_push($result, $reservation);
        }
        if ($reservationSet == null) $reservationSet = [];
        return $result;
    }
}
