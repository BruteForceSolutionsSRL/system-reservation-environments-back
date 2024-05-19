<?php
namespace App\Repositories;

use App\Models\{
    Reservation
};

use App\Models\ClassroomLogs;
use App\Repositories\{
    ReservationStatusRepository as ReservationStatuses,
    ClassroomLogsRepository
}; 

use App\Service\ServiceImplementation\TimeSlotServiceImpl;
use Carbon\Carbon;
use DateTime;

use Illuminate\Cache\Repository;
class ReservationRepository extends Repository
{
    protected $model; 
    private $timeSlotService;
    private $classroomLog;  
    function __construct($model) 
    {
        $this->model = $model; 
        $this->timeSlotService = new TimeSlotServiceImpl();
        $this->classroomLog = new ClassroomLogsRepository(ClassroomLogs::class); 
    }

    /**
     * Retrieve a single Reservation in array by its ID
     * @param int $id
     * @return array
     */
    public function getReservation(int $id): array
    {
        return $this->formatOutput($this->model::with([
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

    /**
     * Retrieve a list of all reservations
     * @param none
     * @return array
     */
    public function getAllReservations(): array
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

    /**
     * Retrieve a list of reservations except pending request
     * @param none
     * @return array
     */
    public function getReservationsWithoutPendingRequest(): array
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
        ])->where('reservation_status_id', '!=', ReservationStatuses::pending())
            ->orderBy('date')
            ->get()->map(
                function ($reservation)
                {
                    return $this->formatOutput($reservation);
                }
            )->toArray();
    }

    /**
     * Retrieve a list of pending request
     * @param none
     * @return array
     */
    public function getPendingRequest(): array
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
        ])->where('date', '>=', Carbon::now()->format('Y-m-d'))
            ->where('reservation_status_id', ReservationStatuses::pending())
            ->orderBy('date')->get()->map(
                function ($reservation)
                {
                    return $this->formatOutput($reservation);
                }
            )->toArray();
    }

    /**
     * Retrieve a list of all request accepted/pending by teacher ID
     * @param int $teacherId
     * @return array
     */
    public function getRequestByTeacher(int $teacherId): array
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

    /**
     * Retrieve a list of all request by teacher ID
     * @param int $teacherId
     * @return array
     */
    public function getAllRequestByTeacher(int $teacherId): array
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
     * Retrieve a list of all request by teacher ID
     * @param int $teacherId
     * @return array
     */
    public function getReservationsWithoutPendingRequestByTeacher(int $teacherId): array
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
        ])->where('reservation_status_id', '!=', ReservationStatuses::pending())
            ->whereHas('teacherSubjects', function ($query) use ($teacherId) {
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
            'classrooms' => $classrooms->map(
                function ($classroom) use ($reservation) 
                {
                    $classroomData = $this->classroomLog->retriveLastClassroom(
                        [
                            'classroom_id' => $classroom->id,
                            'date' => $reservation->created_at 
                        ]
                    );
                    return [
                        'classroom_name' => $classroomData['classroom_name'],
                        'capacity' => $classroomData['capacity'],
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
        $reservationSet = $this->model::whereHas(
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
            $time = $this->timeSlotService->getTimeSlotsSorted($reservation->timeSlots);
            if (!($time[1] <= $times[0] || $time[0] >= $times[1])) {
                array_push($refinedReservationSet, $reservation);
            }
        }
        return $refinedReservationSet;
    }

    /**
     * Store a new Reservation request
     * @param array $data
     * @return Reservation
     */
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

    /**
     * Retrieve a list of reservations with a specified classroom with status
     * @param int $classroomId
     * @param array $statuses
     * @return array
     */
    public function getReservationsByClassroomAndStatuses(
        int $classroomId,
        array $statuses
    ): array
    {
        return $this->model::whereHas(
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
        )->get()
        ->map(
            function ($reservation)
            {
                return $this->formatOutput($reservation);
            }
        )->toArray();
    }
}
