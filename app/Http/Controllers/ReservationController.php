<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Reservation; 
use App\Models\ReservationStatus;

class ReservationController extends Controller
{
    /**
     * index function retrieves all reservations. 
     */
    public function index() 
    {
        $reservations = Reservation::with([
            'reservationStatus:id,status',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,teacher_id,university_subject_id',
            'teacherSubjects.teacher:id,person_id',
            'teacherSubjects.teacher.person:id,name,last_name,email,phone_number',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,floor,block_id,classroom_type_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return ReservationController::formatOutput($reservation);
        });

        return response()->json($formattedReservations, 200);
    }

    private function formatOutput($reservation)
    {
        return [
            'id' => $reservation->id,
            'numberOfStudents' => $reservation->number_of_students,
            'date' => $reservation->date,
            'reason' => $reservation->reason,
            'createdAt' => $reservation->created_at,
            'updatedAt' => $reservation->updated_at,
            'reservationStatus' => $reservation->reservationStatus,
            'schedule' => $reservation->timeSlots->map(function ($timeSlot) {
                return [
                    'id' => $timeSlot->id,
                    'time' => $timeSlot->time
                ];
            }),
            'asignament' => $reservation->teacherSubjects->map(function ($teacherSubject) {
                return [
                    'id' => $teacherSubject->id,
                    'groupNumber' => $teacherSubject->group_number,
                    'teacher' => [
                        'id' => $teacherSubject->teacher->id,
                        'person' => $teacherSubject->teacher->person
                    ],
                    'universitySubject' => $teacherSubject->universitySubject
                ];
            }),
            'classrooms' => $reservation->classrooms->map(function ($classroom) {
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'capacity' => $classroom->capacity,
                    'floor' => $classroom->floor,
                    'block' => $classroom->block,
                    'classroomType' => $classroom->classroomType
                ];
            })
        ];
    }

    public function show($id)
    {
        $reservation = Reservation::with([
            'reservationStatus:id,status',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,teacher_id,university_subject_id',
            'teacherSubjects.teacher:id,person_id',
            'teacherSubjects.teacher.person:id,name,last_name,email,phone_number',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,floor,block_id,classroom_type_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->findOrFail($id);

        if ($reservation == null) {
            return response()->json(['error'
                    => 'There is no reservation, try it later?'], 404);
        }

        return ReservationController::formatOutput($reservation);
    }

    public function rejectReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation == null) {
            return response()->json(['error'
                    => 'There is no reservation, try it later?'], 404);
        }

        if ($reservation->reservation_status_id == 2) {
            return response()->json(['error'
                    => 'This request has already been rejected'], 200);
        }

        $reservation->reservation_status_id = 2;
        $reservation->save();

        return response()->json(['error'
                    => 'Request rejected'], 200);
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
