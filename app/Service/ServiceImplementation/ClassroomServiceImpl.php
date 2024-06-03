<?php

namespace App\Service\ServiceImplementation;

use App\Repositories\BlockRepository;
use App\Service\ClassroomService;

use App\Models\{
    Classroom,
    ClassroomLogs,
    Reservation,
    TimeSlot,
    Block,
    ClassroomStatus
};

use App\Repositories\{
    ClassroomRepository,
    ReservationRepository,
    ReservationStatusRepository as ReservationStatuses,
    TimeSlotRepository,
    ClassroomStatusRepository,
    ClassroomLogsRepository
};

class ClassroomServiceImpl implements ClassroomService
{
    private $classroomRepository;
    private $reservationRepository;
    private $timeSlotRepository;
    private $blockRepository;
    private $classroomStatusRepository;
    private $classroomLogRepository;

    private $reservationService;
    private $timeSlotService;

    public function __construct()
    {
        $this->classroomRepository = new ClassroomRepository(Classroom::class);
        $this->reservationRepository = new ReservationRepository(Reservation::class);
        $this->timeSlotRepository = new TimeSlotRepository(TimeSlot::class);
        $this->blockRepository = new BlockRepository(Block::class);
        $this->classroomStatusRepository = new ClassroomStatusRepository(ClassroomStatus::class);

        $this->timeSlotService = new TimeSlotServiceImpl();
        $this->reservationService = new ReservationServiceImpl();
        $this->classroomLogRepository = new ClassroomLogsRepository(ClassroomLogs::class);
    }

    /**
     * Retrieve a list of classrooms through a status
     * @param string $statuses
     * @return array
     */
    public function getAllClassrooms(string $statuses): array
    {
        $idStatuses = $this->classroomStatusRepository->getStatusesIdByName($statuses);
        return $this->classroomRepository->getClassrooomsByStatus($idStatuses);
    }

    /**
     * Function to return to information about a classroom with it's statistics
     * @param none
     * @return array
     */
    public function getAllClassroomsWithStatistics(): array
    {
        $classroomsStatistics = $this->classroomRepository->getAllClassroomsWithStatistics();
        return $classroomsStatistics;
    }

    /**
     * 
     * @param none
     * @return array
     */
    public function getAllAvailableClassrooms(): array
    {
        return $this->classroomRepository->getClassrooomsByStatus(
            [
                ClassroomStatusRepository::available(),
                ClassroomStatusRepository::disabled()
            ]
        );
    }

    /**
     * 
     * @param int $id
     * @return array
     * 
     */
    public function getClassroomByID(int $id): array
    {
        return $this->classroomRepository->getClassroomById($id);
    }

    /**
     * 
     * @param int $classroomId
     * @return bool
     */
    public function isDeletedClassroom(int $classroomId): bool
    {
        $isdeleted = $this->classroomRepository->isDeletedClassroom($classroomId);
        return $isdeleted;
    }

    /**
     * To retrieve array classrooms within block ID by its disponibility
     * @param int $blockID
     * @return array
     */
    public function getDisponibleClassroomsByBlock(int $blockId): array
    {
        return $this->classroomRepository->getDisponibleClassroomsByBlock($blockId);
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
     * Update with all data previously validated
     * @param array $data
     * @return string
     */
    public function update(array $data): string
    {
        $classroom = $this->classroomRepository->getClassroomById(
            $data['classroom_id']
        );
        $modifiedClassroom = $this->classroomRepository->update($data);
        if ($classroom['classroom_status_id'] != $modifiedClassroom['classroom_status_id']) {
            $reservations = $this->reservationService->getActiveReservationsByClassroom(
                $classroom['classroom_id']
            );
            foreach ($reservations as $reservation)
                if ($reservation['repeat'] == 0) {
                    $this->reservationService->reject($reservation['reservation_id']);
                }
            // modulo para enviar las notificaciones :V
        }
        return "El ambiente fue actualizado correctamente";
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

        $times = $data['time_slot_id'];
        sort($times);

        $acceptedStatus = ReservationStatuses::accepted();
        $pendingStatus = ReservationStatuses::pending();

        foreach ($data['classroom_id'] as $classroomId) {
            $classroom = $this->classroomRepository->getClassroomById($classroomId);

            if (count($classroom) == 0) continue;

            $element = array();
            $element['classroom_name'] = $classroom['classroom_name'];

            $reservations = $this->reservationRepository
                ->getActiveReservationsWithDateStatusAndClassroom(
                    [$acceptedStatus, $pendingStatus],
                    $data['date'],
                    $classroomId
                );

            for ($timeSlotId = $times[0]; $timeSlotId <= $times[1]; $timeSlotId++) {
                $index = $this->timeSlotRepository
                    ->getTimeSlotById($timeSlotId)['time'];
                $element[$index] = [
                    'valor' => 0,
                    'message' => 'Disponible'
                ];
            }

            foreach ($reservations as $reservation) {
                $timesReservation = $this->timeSlotService
                    ->getTimeSlotsSorted($reservation->timeSlots);
                $isAccepted = $reservation->reservation_status_id == $acceptedStatus;

                for (
                    $timeSlotId = max($timesReservation[0], $times[0]);
                    $timeSlotId <= min($times[1], $timesReservation[1]);
                    $timeSlotId++
                ) {
                    $index = $this->timeSlotRepository->getTimeSlotById($timeSlotId)['time'];

                    if ($element[$index]['valor'] == 1) continue;
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
        $classroomSet = $this->classroomRepository
            ->getClassroomsByBlock($data['block_id']);
        $classroomSets = [];
        $max_floor = $this->blockRepository->getBlock($data['block_id'])['block_maxfloor'];

        for ($i = 0; $i <= $max_floor; $i++) {
            $classroomSets[$i] = [
                "quantity" => 0,
                "list" => array()
            ];
        }
        $acceptedStatus = ReservationStatuses::accepted();
        foreach ($classroomSet as $classroom) {

            $reservations = $this->reservationRepository
                ->getActiveReservationsWithDateStatusAndClassroom(
                    [$acceptedStatus],
                    $data['date'],
                    $classroom['classroom_id']
                );
            if (count($reservations) != 0) continue;
            $classroomSets[$classroom['floor']]['quantity'] += $classroom['capacity'];
            array_push($classroomSets[$classroom['floor']]['list'], $classroom);
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
            return ['message' => 'No existe una sugerencia apropiada'];
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
                    $index = $j + ($classroom['capacity']);
                    if ($index > $MAX_LEN) continue;
                    $len = $dp[$j] + 1;
                    if (($dp[$index] == -1) || ($dp[$index] > $len)) {
                        $dp[$index] = $len;
                        $pointerDp[$index] = $classroom['classroom_id'];
                    }
                }

        $bestSuggest = $data['quantity'];
        for ($i = $data['quantity']; $i <= $MAX_LEN; $i++)
            if ($dp[$i] != -1)
                if (($dp[$bestSuggest] == -1) || ($dp[$bestSuggest] > $dp[$i]))
                    $bestSuggest = $i;

        $res = array();
        $piv = $bestSuggest;
        if ($dp[$piv] == -1)
            return ['message' => 'No existe una sugerencia apropiada'];

        while ($piv != 0) {
            $classroom = $this->classroomRepository
                ->getClassroomById($pointerDp[$piv]);
            array_push($res, $classroom);
            $piv -= $classroom['capacity'];
        }

        return $res;
    }

    /**
     * Function to retrive all statuses except deleted
     * @param none
     * @return array
     */
    public function getClassroomStatuses(): array
    {
        return $this->classroomStatusRepository->getStatuses();
    }

    /**
     * Function to retrieve a classroom not deleted and with a date earlier than the provided date
     * @param array $data
     * @return array
     */
    public function retriveLastClassroom(array $data): array
    {
        return $this->classroomLogRepository->retriveLastClassroom($data);
    }

    /**
     * Function to delete classroom through classroom ID and cancel or reject all reservations
     * @param int $classromId
     * @return array
     */
    public function deleteByClassroomId(int $classroomId): array
    {
        $this->reservationService->cancelAndRejectReservationsByClassroom($classroomId);
        $this->classroomRepository->deleteByClassroomId($classroomId); 
        return ['message' => 'Ambiente eliminado exitosamente.'];
    }

    /**
     * 
     * @param array $data
     * @return array
     */
    public function getClassroomStats(array $data): array
    {
        return $this->classroomRepository->getClassroomStats($data);
    }
}
