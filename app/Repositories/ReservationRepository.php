<?php

namespace App\Repositories;

use App\Models\{
    Reservation,
    ReservationStatus,
    ClassroomLogs,
};


use App\Repositories\{
    ReservationStatusRepository as ReservationStatuses,
    ClassroomLogsRepository
};

use App\Service\ServiceImplementation\TimeSlotServiceImpl;
use Carbon\Carbon;
use DateTime;

use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\DB;

class ReservationRepository extends Repository
{
    protected $model;
    private $timeSlotService;
    private $classroomLog;
    function __construct()
    {
        $this->model = Reservation::class;

        $this->timeSlotService = new TimeSlotServiceImpl();

        $this->classroomLog = new ClassroomLogsRepository();
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
            function ($reservation) {
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
                function ($reservation) {
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
                function ($reservation) {
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
            ->whereIn(
                'reservation_status_id',
                [
                    ReservationStatuses::accepted(),
                    ReservationStatuses::pending()
                ]
            )->whereHas(
                'teacherSubjects',
                function ($query) use ($teacherId) {
                    $query->where('person_id', $teacherId);
                }
            )->orderBy('date')->get()->map(
                function ($reservation) {
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
            function ($reservation) {
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
                function ($reservation) {
                    return $this->formatOutput($reservation);
                }
            )->toArray();
    }

    /**
     * Function to format from Reservation class to array
     * @param Reservation $reservation
     * @return array
     */
    public function formatOutput(Reservation $reservation): array
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
                    'person_id' => $person->id,
                ];
            }),
            'block_name' => $classrooms->first()->block->name,
            'classrooms' => $classrooms->map(
                function ($classroom) use ($reservation) {
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
                }
            ),
            'reason_name' => $reservationReason->reason,
            'priority' => $priority,
            'reservation_status' => $reservationStatus->status,
            'repeat' => $reservation->repeat,
            'date' => $reservation->date,
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
    ): array {
        $reservationSet = $this->model::whereHas(
            'classrooms',
            function ($query) use ($classroomId) {
                $query->where('classroom_id', $classroomId);
            }
        )->where(
            function ($query) use ($statuses) {
                foreach ($statuses as $status)
                    $query->orWhere('reservation_status_id', $status);
            }
        )->where(
            function ($query) use ($date) {
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
    ): array {
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
     * @return array
     */
    public function save(array $data): array
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

        return $this->formatOutput($reservation);
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
            function ($query) use ($classroomId) {
                $query->where('classroom_id', $classroomId);
            }
        )->where(
            function ($query) use ($statuses) {
                foreach ($statuses as $status)
                    $query->orWhere('reservation_status_id', $status);
            }
        )->get()
            ->map(
                function ($reservation) {
                    return $this->formatOutput($reservation);
                }
            )->toArray();
    }

    /**
     * Function to retrive reservations accepted and rejected by classroom
     * @param int $classroomId
     * @return array
     */
    public function getAcceptedAndPendingReservationsByClassroom(int $classroomId): array
    {
        $acceptedStatus = ReservationStatus::where('status', 'ACCEPTED')->first();
        $pendingStatus = ReservationStatus::where('status', 'PENDING')->first();

        if (!$acceptedStatus || !$pendingStatus) {
            return ['error' => 'Reservation statuses not found.'];
        }



        $acceptedStatusId = $acceptedStatus->id;
        $pendingStatusId = $pendingStatus->id;

        $reservations = Reservation::whereHas('classrooms', function ($query) use ($classroomId) {
            $query->where('classroom_id', $classroomId);
        })->where(function ($query) use ($acceptedStatusId, $pendingStatusId) {
            $query->where('reservation_status_id', $acceptedStatusId)
                ->orWhere('reservation_status_id', $pendingStatusId);
        })->get(['id', 'reservation_status_id']);

        $acceptedReservations = [];
        $pendingReservations = [];

        foreach ($reservations as $reservation) {
            if ($reservation->reservation_status_id === $acceptedStatusId) {
                $acceptedReservations[] = $reservation->id;
            } elseif ($reservation->reservation_status_id === $pendingStatusId) {
                $pendingReservations[] = $reservation->id;
            }
        }

        return [
            'accepted' => $acceptedReservations,
            'pending' => $pendingReservations,
        ];
    }

    /**
     * Function to format from function "getAllReservationsByClassroom"
     * @param array $classroomId
     * @return array
     */
    public function formatOutputGARBC(array $reservations): array
    {
        if (empty($reservations)) return [];
        $formatReservations = [];
        foreach ($reservations as $reservation) {

            $formatReservations[] = [
                'reservation_id' => $reservation['reservation_id'],
                'subject_name' => $reservation['subject_name'],
                'reason_name' => $reservation['reason_name'],
                'reservation_date' => $reservation['reservation_date'],
                'quantity' => $reservation['quantity'],
                'reservation_status' => $reservation['reservation_status'],
            ];
        }
        return $formatReservations;
    }

    /**
     * Retrieve a list of reports based on the given data.
     * 
     * @param array $data
     * @return array
     */
    public function getReports(array $data): array
    {
        $query = DB::table('reservations')
            ->join('reservation_teacher_subject', 'reservations.id', '=', 'reservation_teacher_subject.reservation_id')
            ->join('teacher_subjects', 'reservation_teacher_subject.teacher_subject_id', '=', 'teacher_subjects.id')
            ->join('people', 'teacher_subjects.person_id', '=', 'people.id')
            ->join('classroom_reservation', 'reservations.id', '=', 'classroom_reservation.reservation_id')
            ->join('classrooms', 'classroom_reservation.classroom_id', '=', 'classrooms.id')
            ->join('blocks', 'classrooms.block_id', '=', 'blocks.id')
            ->join('reservation_time_slot', 'reservations.id', '=', 'reservation_time_slot.reservation_id')
            ->join('time_slots', 'reservation_time_slot.time_slot_id', '=', 'time_slots.id')
            ->select(
                'reservations.id as reservation_id',
                'reservations.date',
                DB::raw('CONCAT(people.name, " ", people.last_name) as teacher_name'),
                'blocks.name as block_name',
                'classrooms.name as classroom_name',
                'time_slots.time as time_slot_time',
                'reservations.created_at as date_send',
                'reservations.updated_at as date_approval',
                'reservations.reservation_status_id'
            )
            ->whereBetween('reservations.date', [$data['date_start'], $data['date_end']])
            ->whereIn('reservations.reservation_status_id', [
                ReservationStatuses::accepted(), 
                ReservationStatuses::rejected(), 
                ReservationStatuses::cancelled()]
            );

        if (!empty($data['person_id'])) {
            $query->where('people.id', $data['person_id']);
        }

        if (!empty($data['block_id'])) {
            $query->where('blocks.id', $data['block_id']);
        }

        if (!empty($data['classroom_id'])) {
            $query->where('classrooms.id', $data['classroom_id']);
        }

        if (!empty($data['reservation_status_id'])) {
            $query->where('reservations.reservation_status_id', $data['reservation_status_id']);
        }

        if (!empty($data['university_subject_id'])) {
            $query->where('teacher_subjects.university_subject_id', $data['university_subject_id']);
        }

        $acceptedCount = 0;
        $rejectedCount = 0;
        $canceledCount = 0;

        $results = $query->orderBy('reservations.date')->get()->toArray();

        if (empty($results)) {
            return [
                'accepted_reservations' => 0,
                'rejected_reservations' => 0,
                'canceled_reservations' => 0,
                'total_reservations' => 0,
                'report' => []
            ];
        }

        /* return $results; */

        $formattedResults = [];
        foreach ($results as $result) {
            $reservationId = $result->reservation_id;
            if (!isset($formattedResults[$reservationId])) {
                $formattedResults[$reservationId] = [
                    'reservation_id' => $result->reservation_id,
                    'date' => Carbon::parse($result->date)->format('Y-m-d'),
                    'date_send' => Carbon::parse($result->date_send)->format('Y-m-d'),
                    'date_approval' => '',
                    'block_name' => $result->block_name,
                    'teachers' => [],
                    'classrooms' => [],
                    'time_slots' => [],
                ];
                if ($result->reservation_status_id === ReservationStatuses::accepted()) {
                    $acceptedCount++;
                    $formattedResults[$reservationId]['date_approval'] = $result->date_approval;               
                } else if ($result->reservation_status_id === ReservationStatuses::cancelled()) {
                    $canceledCount++;
                    $formattedResults[$reservationId]['date_approval'] = Carbon::parse($result->date_approval)->format('Y-m-d'); 
                } else if ($result->reservation_status_id === ReservationStatuses::rejected()) {
                    $rejectedCount++;
                    $formattedResults[$reservationId]['date_approval'] = 'N/A';
                }
            }

            if (!in_array($result->teacher_name, $formattedResults[$reservationId]['teachers'])) {
                $formattedResults[$reservationId]['teachers'][] = $result->teacher_name;
            }

            if (!in_array($result->classroom_name, $formattedResults[$reservationId]['classrooms'])) {
                $formattedResults[$reservationId]['classrooms'][] = $result->classroom_name;
            }

            if (!in_array($result->time_slot_time, $formattedResults[$reservationId]['time_slots'])) {
                $formattedResults[$reservationId]['time_slots'][] = $result->time_slot_time;
            }
        }

        $finalResults = [];
        foreach ($formattedResults as $formattedResult) {
            $classrooms = implode(', ', $formattedResult['classrooms']);
            $timeSlots = $formattedResult['time_slots'];
            $timeSlots = ($timeSlots[0]).'-'.($timeSlots[count($timeSlots)-1]);
            foreach ($formattedResult['teachers'] as $teacher) {
                $finalResults [] = [
                    'reservation_id' => $formattedResult['reservation_id'],
                    'date' => $formattedResult['date'],
                    'date_send' => $formattedResult['date_send'],
                    'date_approval' => $formattedResult['date_approval'],
                    'block_name' => $formattedResult['block_name'],
                    'teacher' => $teacher,
                    'classrooms' => $classrooms,
                    'time_slots' => $timeSlots
                ];
            }
        }

        $totalCount = $acceptedCount + $rejectedCount + $canceledCount;

        //$formattedResults = array_values($formattedResults);

        return [
            'accepted_reservations' => $acceptedCount,
            'rejected_reservations' => $rejectedCount,
            'canceled_reservations' => $canceledCount,
            'total_reservations' => $totalCount,
            'report' => $finalResults
        ];
    }
}
