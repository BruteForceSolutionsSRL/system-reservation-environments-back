<?php
namespace App\Repositories; 

use App\Models\Reservation;

use App\Repositories\ReservationStatusRepository as ReservationStatuses; 

use Carbon\Carbon;
use DateTime; 

use Illuminate\Cache\Repository;
class ReservationRepository extends Repository 
{
    protected $model; 

    function __construct($model) 
    {
        $this->model = $model; 
    }
    public function getReservation($id) 
    {
        return $this->formatOutput(Reservation::with([
            'reservationStatus:id,status',
            'reservationReason:id,reason',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,person_id,university_subject_id',
            'teacherSubjects.person:id,name,last_name',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,block_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->find($id));
    }
    public function getAllReservations() 
    {
        return $this->model::with([
            'reservationStatus:id,status',
            'reservationReason:id,reason',
            'timeSlots:id,time',
            'teacherSubjects:id,group_number,person_id,university_subject_id',
            'teacherSubjects.person:id,name,last_name',
            'teacherSubjects.universitySubject:id,name',
            'classrooms:id,name,capacity,block_id',
            'classrooms.block:id,name',
            'classrooms.classroomType:id,description'
        ])->orderBy('date')->get()->map(
            function ($reservation) 
            {
                return $this->formatOutput($reservation);
            }
        )->toArray();
    }
    public function getPendingRequest()
    {
        return Reservation::with([
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
            ->where('reservation_status_id', ReservationStatuses::pending())
            ->orderBy('date')->get()->map(
                function ($reservation) 
                {
                    return $this->formatOutput($reservation);
                }
            )->toArray();
    }
    public function getRequestByTeacher($teacherId) 
    {
        return Reservation::with([
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
            ->whereIn('reservation_status_id', [
                ReservationStatuses::accepted(), 
                ReservationStatuses::pending()]
            )->whereHas('teacherSubjects', 
                function ($query) use ($teacherId) 
                {
                    $query->where('person_id', $teacherId);
                }
            )->orderBy('date')->get()->map(
                function ($reservation) 
                {
                    return $this->formatOutput($reservation);
                }
            )->toArray();
    }
    public function getAllRequestByTeacher($teacherId) 
    {
        return Reservation::with([
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
            })->orderBy('date')->get()->map(
                function ($reservation) 
                {
                    return $this->formatOutput($reservation);
                }
            )->toArray();
    }
    /**
     * Function to format from Reservation class to array 
     * @param Reservation $reservation
     * @return array
     */
    private function formatOutput(Reservation $reservation): array
    {
        if ($reservation == null) return []; 
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
            'time_slot' => $timeSlots->map(function ($timeSlot) {
                return $timeSlot->time;
            }),
            'groups' => $teacherSubjects->map(function ($teacherSubject) {
                $person = $teacherSubject->person;

                return [
                    'teacher_name' => $person->name . ' ' . $person->last_name,
                    'group_number' => $teacherSubject->group_number,
                ];
            }),
            'block_name' => $classrooms->first()->block->name,
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
    /**
     * Function to retrieve a list of all active reservations
     * @param array $statuses
     * @param string $date format must be: 'Y-m-d'
     * @param int $classroomId
     * @return array
     */
    public function getActiveReservationsWithDateStatusAndClassroom(
        array $statuses,
        string $date,
        int $classroomId
    ): array
    {
        $reservationSet = Reservation::whereHas(
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
                function ($query) use ($date)
                {
                    $query->where('date', '>=', date('Y-m-d'));
                    $query->where('date', $date);
                    $query->orWhere('repeat', '>', 0);
                }
            )->get();
        $result = []; 
        foreach ($reservationSet as $reservation) {
            $initialDate = new DateTime($date);
            if ($reservation->repeat > 0) {
                $goalDate = new DateTime($reservation->date);
                $repeat = $reservation->repeat;

                $difference = $initialDate->diff($goalDate)->days;
                if ($difference % $repeat == 0)
                    array_push($result, $reservation);
            } else array_push($result, $reservation);
        }
        return $result;
    }
    /**
     * Function to retrieve a list of all active reservations
     * @param array $statuses
     * @param string $date format must be: 'Y-m-d'
     * @param int $classroomId
     * @param array $times 
     * @return array
     */
    public function getActiveReservationsWithDateStatusClassroomTimes(
        array $statuses,
        string $date,
        int $classroomId,
        array $times
    ): array
    {
        $reservations = $this->getActiveReservationsWithDateStatusAndClassroom(
            $statuses, 
            $date, 
            $classroomId
        );
        $refinedReservationSet = [];
        foreach ($reservations as $reservation) {
            $time = $this->getTimeSlotsSorted($reservation->timeSlots);
            if (!($time[1] <= $times[0] || $time[0] >= $times[1])) {
                array_push($refinedReservationSet, $reservation);
            }
        }
        return $refinedReservationSet;
    }
    public function save(array $data): Reservation 
    {
        $reservation = new Reservation();
        $reservation->number_of_students = $data['quantity'];
        $reservation->repeat = $data['repeat'];
        $reservation->date = $data['date'];
        $reservation->reservation_reason_id = $data['reason_id'];
        $reservation->reservation_status_id = ReservationStatuses::pending();
        $reservation->save();

        $reservation->teacherSubjects()->attach($data['group_id']);
        $reservation->classrooms()->attach($data['classroom_id']);
        $reservation->timeSlots()->attach($data['time_slot_id']);
        
        return $reservation; 
    }
    private function getTimeSlotsSorted($timeSlots): array
    {
        $array = array(); 
        foreach ($timeSlots as $timeSlot) 
            array_push($array, $timeSlot->id);
        sort($array); 
        return $array;
    }
}