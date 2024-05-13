<?php

namespace App\Service\ServiceImplementation;

use App\Service\ClassroomService;

use App\Models\{
    Classroom,
    Reservation,
    TimeSlot,
    Block,
};

use App\Repositories\{
    ClassroomRepository,
    ReservationRepository,
    ReservationStatusRepository as ReservationStatuses,
    TimeSlotRepository,
};

class ClassroomServiceImpl implements ClassroomService
{
    private $classroomRepository;
    private $reservationRepository;
    private $timeSlotRepository;
    function __construct()
    {
        $this->classroomRepository = new ClassroomRepository(Classroom::class);
        $this->reservationRepository = new ReservationRepository(Reservation::class);
        $this->timeSlotRepository = new TimeSlotRepository(TimeSlot::class);
    }
    /**
     * Retrieve a list of all classrooms
     * @param none
     * @return array
     */
    public function getAllClassrooms(): array
    {
        return $this->classroomRepository->getAllClassrooms();
    }

    /**
     * To retrieve array available classrooms within block ID
     * @param int $blockID
     * @return array
     */
    public function availableClassroomsByBlock(int $blockId): array
    {
        return $this->classroomRepository->availableClassroomsByBlock($blockId);
    }

    /**
     * To retrieve array classrooms within block ID
     * @param int $blockID
     * @return array
     */
    public function getClassroomsByBlock(int $blockId): array
    {
        return $this->classroomRepository->getClassroomsByBlock($blockId);
    }

    /**
     * Save a classroom with all data previously validated
     * @param array $data
     * @return string
     */
    public function store(array $data): string
    {
        $this->classroomRepository->save($data);
        return "El ambiente fue creado exitosamente.";
    }

    /**
     * Function to retrieve disponibility
     * status for all selected classrooms
     * @param array $data
     * @return array
     */
    public function getClassroomByDisponibility(array $data): array
    {
        $classroomList = [];

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

        $acceptedStatus = ReservationStatuses::accepted();
        $pendingStatus = ReservationStatuses::pending();

        foreach ($data['classroom_id'] as $classroomId) {

            $classroom = $this->classroomRepository->getClassroomById($classroomId);

            $element = array();
            $element['classroom_name'] = $classroom->name;

            $reservations = $this->reservationRepository->getActiveReservationsWithDateStatusAndClassroom(
                [$acceptedStatus, $pendingStatus],
                $data['date'],
                $classroomId
            );

            for ($timeSlotId = $initialTime; $timeSlotId <= $endTime; $timeSlotId++) {
                $timeSlot = $this->timeSlotRepository->getTimeSlotById($timeSlotId);
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
                    $timeSlot = $this->timeSlotRepository->getTimeSlotById($timeSlotId);
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
        return $classroomList;
    }

    /**
     * Function suggest a set of classrooms for a booking
     * @param array $data
     * @return array
     */
    public function suggestClassrooms(array $data): array
    {
        $classroomSet = Classroom::where('block_id', $data['block_id'])
            ->get();
        $classroomSets = [];
        $max_floor = Block::find($data['block_id'])->max_floor;

        for ($i = 0; $i <= $max_floor; $i++) {
            $classroomSets[$i] = [
                "quantity" => 0,
                "list" => array()
            ];
        }
        $acceptedStatus = ReservationStatuses::accepted();
        foreach ($classroomSet as $classroom) {
            $reservations = $this->reservationRepository->getActiveReservationsWithDateStatusAndClassroom(
                [$acceptedStatus],
                $data['date'],
                $classroom->id
            );
            if (count($reservations) != 0) continue;
            $classroomSets[$classroom->floor]['quantity'] += $classroom->capacity;
            array_push($classroomSets[$classroom->floor]['list'], $classroom);
        }

        $MAX_LEN = 1e4 + 10;
        $dp = array_fill(0, $MAX_LEN + 1, $max_floor + 100);
        $pointerDp = array_fill(0, $MAX_LEN + 1, -1);
        $dp[0] = 0;
        for ($i = 0; $i <= $max_floor; $i++)
            for ($j = $MAX_LEN; $j > -1; $j--)
                if ($dp[$j] < $max_floor + 100) {
                    $index = $j + $classroomSets[$i]['quantity'];
                    if ($index > $MAX_LEN) break;
                    $len = $dp[$j] + count($classroomSets[$i]['list']);

                    if ($dp[$index] > $len) {
                        $dp[$index] = $len;
                        $pointerDp[$index] = $i;
                    }
                }

        $bestSuggest = $dp[$data['quantity']];

        for ($i = $data['quantity']; $i <= min($data['quantity'] + 300, 1e5); $i++)
            if (($bestSuggest == -1) || ($dp[$bestSuggest] > $dp[$i]))
                $bestSuggest = $i;

        if ($pointerDp[$bestSuggest] == -1) {
            return ['No existe una sugerencia apropiada'];
        }

        $classrooms = array();
        $piv = $bestSuggest;
        while ($piv > 0) {
            $itemId = $pointerDp[$piv];
            foreach ($classroomSets[$itemId]['list'] as $classroom)
                array_push($classrooms, $classroom);
            $piv = $piv - $classroomSets[$itemId]['quantity'];
        }

        $dp = array_fill(0, $MAX_LEN + 1, -1);
        $pointerDp = array_fill(0, $MAX_LEN, -1);
        $dp[0] = 0;
        foreach ($classrooms as $classroom)
            for ($j = $MAX_LEN; $j > -1; $j--)
                if ($dp[$j] != -1) {
                    $index = $j + ($classroom->capacity);
                    if ($index > $MAX_LEN) continue;
                    $len = $dp[$j] + 1;
                    if (($dp[$index] == -1) || ($dp[$index] > $len)) {
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
            $classroom = $this->classroomRepository->getClassroomById($pointerDp[$piv]);
            array_push($res, $this->classroomRepository->formatOutput($classroom));
            $piv -= $classroom->capacity;
        }

        return $res;
    }
}
