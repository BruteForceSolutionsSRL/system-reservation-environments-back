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
            ->where('reservation_status_id', '=', '3')->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return ReservationController::formatOutput($reservation);
        });

        return response()->json($formattedReservations, 200);
    }

    private function formatOutput(Reservation $reservation)
    {
        $reservationReason = $reservation->reservationReason;
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
                    => 'La solicitud fue rechazada con exito'], 200);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = $this->validateReservationData($request);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();

            // Creacion de la reserva.
            $reservation = Reservation::create([
                'number_of_students' => $data['number_of_students'],
                'repeat' => 0,
                'date' => $data['date'],
                'reason' => $data['reason'],
                'reservation_status_id' => 3,
            ]);

            // Tabla intermediaria 'reservation_teacher_subject'.
            $reservation->teacherSubjects()->attach($data['teacher_subject_ids'], ['created_at' => now(), 'updated_at' => now()]);

            //Tabla intermediaria 'classroom_reservation'.
            $reservation->classrooms()->attach($data['classroom_ids'], ['created_at' => now(), 'updated_at' => now()]);

            //Tabla intermediaria 'reservation_time_slot'.
            $reservation->timeSlots()->attach($data['time_slot_ids'], ['created_at' => now(), 'updated_at' => now()]);

            DB::commit();
            return response()->json(['message' => '¡Reservación creada exitosamente!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateReservationData(Request $request)
    {
        return Validator::make($request->all(), [
            'number_of_students' => 'required|integer',
            'date' => 'required|date',
            'reason' => 'required|string',
            'teacher_subject_ids.*' => 'required|exists:teacher_subjects,id',
            'classroom_ids.*' => 'required|exists:classrooms,id',
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
        ], [
            'number_of_students.required' => 'El número de estudiantes es obligatorio.',
            'number_of_students.integer' => 'El número de estudiantes debe ser un valor entero.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser un formato válido.',
            'reason.required' => 'El motivo de la reserva es obligatorio.',
            'reason.string' => 'El motivo de la reserva debe ser un texto.',
            'teacher_subject_ids.*.required' => 'Se requiere al menos una asignatura de profesor.',
            'teacher_subject_ids.*.exists' => 'Una de las asignaturas de profesor seleccionadas no es válida.',
            'classroom_ids.*.required' => 'Se requiere al menos una aula.',
            'classroom_ids.*.exists' => 'Una de las aulas seleccionadas no es válida.',
            'time_slot_ids.*.required' => 'Se requieren los periodos de tiempo.',
            'time_slot_ids.*.exists' => 'Uno de los periodos de tiempo seleccionados no es válido.',
            'time_slot_ids.required' => 'Se requieren dos periodos de tiempo.',
            'time_slot_ids.array' => 'Los periodos de tiempo deben ser un arreglo.',
        ]);
    }

    /**
     * @description:
     * Endpoint to assign reservations
     * if fulfill no overlapping with assigned
     * reservations.
     */
    public function assign(Request $request)
    {
        try {

            $reservation = Reservation::findOrFail($request->input('id'));
            if ($reservation==null) {
                return response()
                        ->json(['error'=>'There is no reservation, try it later?'], 404);
            }

            $reservationStatus = $reservation->reservationStatus;
            if ($reservationStatus->status!='pending') {
                return response()
                        ->json(['message'=> 'This reservation was reviewed'], 200);
            }

            $ok = $this->checkAvailibility($reservation);
            if ($ok==false) {
                return response()
                        ->json(['message'=> 'Already occupied classroom(s)'], 200);
            }

            $acceptedStatus = ReservationStatus::find(1);

            $reservation->reservation_status_id = 1;
            $reservation->save();
            return response()->json(['ok'=>1], 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    /**
     * @description:
     * Function to check availability for all classrooms to do a
     * reservation in a step
     */
    private function checkAvailibility($reservation): bool
    {
        try {
            //DB::statement('LOCK TABLE reservations IN SHARE MODE');
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

            foreach ($classrooms as $classroom) {
                $reservations = $this->findAcceptedReservationsByClassroomAndDate(
                    $classroom->id,
                    $date
                );

                $ok = true;
                foreach($reservations as $reservation) {
                    $left = -1;
                    $right = -1;
                    $timeSlotReservations = $reservation->timeSlots;
                    foreach ($timeSlotReservations as $timeSlotIterable) {
                        if ($left==-1) $left = $timeSlotIterable->id;
                        else $right  = $timeSlotIterable->id;
                    }
                    if ($left>$right) {
                        $temp = $left;
                        $left = $right;
                        $right = $temp;
                    }
                    $ok &= ($right<=$init) || ($left>=$end);
                }
                if ($ok==false) {
                    return false;
                }
            }
            //DB::statement('UNLOCK TABLES');
        } catch (Exception $e) {
            //DB::statement('UNLOCK TABLES');
            return false;
        }
        return true;
    }

    /**
     * @description:
     * Function to retrieve all reservations with equal date and classroom
     */
    private function findAcceptedReservationsByClassroomAndDate($classroomId, $date)
    {
        $acceptedStatusId = ReservationStatus::where('status', 'accepted')
                                ->get()
                                ->pop()
                                ->id;

        $reservationSet = Reservation::
                        whereHas('classrooms',
                            function ($query) use ($classroomId) {
                                $query -> where ('classroom_id', '=', $classroomId);
                            })
                        ->where('reservation_status_id', $acceptedStatusId)
                        ->where(function($query) use ($date) {
                            $query->where('date', '=', $date)
                                    ->orWhere('repeat', '>', 0);
                        })
                        ->get();
        $result = array();

        foreach ($reservationSet as $reservation) {
            if ($reservation->repeat==0) array_push($result, $reservation);
            else {
                $initialDate = new DateTime($reservation->date);
                $goalDate = new DateTime($date);

                $repeat = $reservation->repeat;
                $difference = $initialDate->diff($goalDate)->days;
                if ($difference%$repeat==0) array_push($result, $reservation);
            }
        }

        return $result;
    }
}
