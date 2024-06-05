<?php

namespace App\Repositories;

use App\Models\{
    Classroom,
    ClassroomType,
    ClassroomStatus as ClassroomStatusModel,
    Block,
    ReservationReason as ReservationReasonModel,
};

use App\Repositories\{
    ClassroomStatusRepository as ClassroomStatus,
    ReservationStatusRepository as ReservationStatus,
    ReservationReasonRepository as ReservationReason,
};

use Illuminate\Cache\Repository;

use Illuminate\Support\Facades\DB;

class ClassroomRepository extends Repository
{
    protected $model;

    private $classroomStatusRepository;
    private $classroomTypeRepository;
    private $blockRepository;
    private $reservationReasonRepository;

    public function __construct($model)
    {
        $this->model = $model;

        $this->classroomStatusRepository = new ClassroomStatusRepository();
        $this->classroomTypeRepository = new ClassroomTypeRepository();
        $this->blockRepository = new BlockRepository();

        $this->reservationReasonRepository = new ReservationReason();
    }

    /**
     * Function to retrieve a list of all classrooms
     * @param int
     * @return array
     */
    public function getAllClassrooms(): array
    {
        return $this->model::where('classroom_status_id', ClassroomStatus::available())
            ->get()
            ->map(
                function ($classroom) {
                    return $this->formatOutput($classroom);
                }
            )->toArray();
    }

    /**
     * Function to retrieve a known deleted classroom 
     * @param int $classroomId
     * @return bool
     */
    public function isDeletedClassroom(int $classroomId): bool
    {
        $classroom = $this->model::find($classroomId);
        if (($classroom != null) &&
            ($classroom->classroom_status_id === ClassroomStatus::deleted())
        ) {
            return true;
        }
        return false;
    }

    /**
     * Function to retrieve a list of enabled and disabled classrooms
     * @param none
     * @return array
     */
    public function getAllClassroomsWithStatistics(): array
    {
        return $this->model::with([
            'block:id,name',
            'classroomType:id,description',
            'classroomStatus:id,name',
            'reservations' => function ($query) {
                $query->select(
                    'reservations.id',
                    'reservations.reservation_status_id',
                    'classroom_reservation.classroom_id'
                );
            }
        ])->where('classroom_status_id', '!=', ClassroomStatus::deleted())
            ->select(
                'id',
                'name',
                'capacity',
                'floor',
                'block_id',
                'classroom_type_id',
                'classroom_status_id'
            )->get()->map(
                function ($classroom) {
                    return $this->formatOutputNamesAndStatistics($classroom);
                }
            )->toArray();
    }

    /**
     * Retrieves a list of all classrooms with a specified status 
     * @param array $statuses
     * @return array
     */
    public function getClassrooomsByStatus(array $idStatuses): array
    {

        return $this->model::where(
            function ($query) use ($idStatuses) {
                foreach ($idStatuses as $status)
                    $query->orWhere('classroom_status_id', $status);
            }
        )->get()->map(
            function ($classroom) {
                return $this->formatOutput($classroom);
            }
        )->toArray();
    }

    /**
     * Function to retrieve a list of classrooms available by block
     * @param int $blockId
     * @return array
     */
    public function getDisponibleClassroomsByBlock(int $blockId): array
    {
        return $this->model::where('classroom_status_id', ClassroomStatus::available())
            ->where('block_id', $blockId)
            ->whereNotIn('id', function ($query) use ($blockId) {
                $query->select('C.id')
                    ->from('classrooms as C')
                    ->join('classroom_reservation as CR', 'C.id', '=', 'CR.classroom_id')
                    ->join('reservations as R', 'CR.reservation_id', '=', 'R.id')
                    ->where('C.block_id', $blockId)
                    ->where('R.reservation_status_id', 1)
                    ->where('R.date', '>=', now()->format('Y-m-d'));
            })->get()
            ->map(function ($classroom) {
                return $this->formatOutput($classroom);
            })->toArray();
    }

    /**
     * Function to retrieve a list of classrooms by block
     * @param int $blockId
     * @return array
     */
    public function getClassroomsByBlock(int $blockId): array
    {
        return $this->model::where('classroom_status_id', ClassroomStatus::available())
            ->where('block_id', $blockId)
            ->get()
            ->map(
                function ($classroom) {
                    return $this->formatOutput($classroom);
                }
            )->toArray();
    }

    /**
     * Function to save data of classroom
     * @param array $data
     * @return array
     */
    public function save(array $data): array
    {
        $classroom = new Classroom();
        $classroom->name = $data['classroom_name'];
        $classroom->capacity = $data['capacity'];
        $classroom->floor = $data['floor_number'];
        $classroom->block_id = $data['block_id'];
        $classroom->classroom_type_id = $data['type_id'];
        $classroom->classroom_status_id = 1;
        $classroom->save();
        return $this->formatOutput($classroom);
    }

    /**
     * Update data of a single classroom 
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        $classroom = $this->model::find($data['classroom_id']);
        $classroom->capacity = $data['capacity'];
        $classroom->floor = $data['floor_number'];
        $classroom->block_id = $data['block_id'];
        $classroom->classroom_type_id = $data['type_id'];
        $classroom->classroom_status_id = $data['status_id'];
        $classroom->save();
        return $this->formatOutput($classroom);
    }

    /**
     * Function to retrieve a classroom by ID
     * @param int $classroomId
     * @return array
     */
    public function getClassroomById(int $classroomId): array
    {
        $classroom = $this->model::find($classroomId);
        if (($classroom != null) &&
            ($classroom->classroom_status_id !== ClassroomStatus::deleted())
        ) {
            return $this->formatOutput($classroom);
        }
        return [];
    }

    /**
     * Function to format a classroom into an array
     * @param Classroom $classroom
     * @return array
     */
    private function formatOutput(Classroom $classroom): array
    {
        $classroomType = $this->classroomTypeRepository->getClassroomTypeById(
            $classroom->classroom_type_id
        );
        $classroomStatus = $this->classroomStatusRepository->getClassroomStatusById(
            $classroom->classroom_status_id
        );

        $block = $this->blockRepository->getBlock($classroom->block_id);

        return [
            'classroom_id' => $classroom->id,
            'classroom_name' => $classroom->name,
            'classroom_type_id' => $classroom->classroom_type_id,
            'classroom_type_name' => $classroomType['type_name'],
            'classroom_status_id' => $classroom->classroom_status_id,
            'classroom_status_name' => $classroomStatus['classroom_status_name'],
            'capacity' => $classroom->capacity,
            'floor' => $classroom->floor,
            'block_id' => $classroom->block_id,
            'block_name' => $block['block_name']
        ];
    }

    /**
     * Function to format a classroom array with names instead of IDs
     * @param Classroom $classroom
     * @return array
     */
    private function formatOutputNamesAndStatistics(Classroom $classroom): array
    {
        $acceptedCount = $classroom->reservations
            ->where('reservation_status_id', ReservationStatus::accepted())
            ->count();
        $rejectedCount = $classroom->reservations
            ->where('reservation_status_id', ReservationStatus::rejected())
            ->count();
        $pendingCount = $classroom->reservations
            ->where('reservation_status_id', ReservationStatus::pending())
            ->count();
        return [
            'id' => $classroom->id,
            'name' => $classroom->name,
            'capacity' => $classroom->capacity,
            'floor' => $classroom->floor,
            'block_name' => $classroom->block->name,
            'type_description' => $classroom->classroomType->description,
            'status_name' => $classroom->classroomStatus->name,
            'statistics' => [
                'accepted_reservations' => $acceptedCount,
                'rejected_reservations' => $rejectedCount,
                'pending_reservations' => $pendingCount,
                'total_reservations' => $acceptedCount + $rejectedCount + $pendingCount,
            ]
        ];
    }

    /**
     * Function to change the status of the classroom to deleted
     * @param int $classroomId
     * @return array
     */
    public function deleteByClassroomId(int $classroomId): array
    {
        $classroom = $this->model::find($classroomId);
        $classroom->classroom_status_id = ClassroomStatus::deleted();
        $classroom->save();
        return $this->formatOutput($classroom);
    }

    /**
     * 
     * @param array $data
     * @return array
     */
    public function getClassroomStats(array $data): array
    {
        $statuses = [
            'accepted'  => ReservationStatus::accepted(),
            'rejected'  => ReservationStatus::rejected(),
            'pending'   => ReservationStatus::pending(),
            'cancelled' => ReservationStatus::cancelled()
        ];
        $chart = $this->getClassroomStatsReservations($data, $statuses);
        $reasons = $this->reservationReasonRepository->getAllReservationReason();
        $table = $this->getClassroomStatsReason($data, $reasons);
        return [
            'chart' => $chart,
            'table' => $table,
        ];
    }

    /**
     * 
     * @param array $data
     * @param array $reasons
     * @return array
     */
    private function getClassroomStatsReason(array $data, array $reasons): array
    {
        $reasonsIds = array_column($reasons, 'reason_id');
        $stats = DB::table('classroom_reservation')
            ->join('reservations', 'classroom_reservation.reservation_id', '=', 'reservations.id')
            ->join('reservation_reasons', 'reservations.reservation_reason_id', '=', 'reservation_reasons.id')  
            ->select(
                'reservations.reservation_reason_id',
                'reservation_reasons.reason',
                DB::raw('COUNT(*) as total_reservations'),
                DB::raw('AVG(reservations.number_of_students) as average_students')
            )
            ->where('classroom_reservation.classroom_id', $data['classroom_id'])
            ->whereBetween('reservations.date',[$data['date_start'], $data['date_end']])
            ->whereIn('reservations.reservation_reason_id', $reasonsIds)
            ->groupBy('reservations.reservation_reason_id', 'reservation_reasons.reason')
            ->get();

        $statsArray = $stats->keyBy('reason')->toArray();
        
        foreach ($reasons as $reason) {
            $reasonName = $reason['reason_name'];
            if (isset($statsArray[$reasonName])) {
                $statsArray[$reasonName] = [
                    'reservation_reason_id' => $reason['reason_id'],
                    'reservation_reason_name' => $reason['reason_name'],
                    'total_reservations' => $statsArray[$reasonName]->total_reservations,
                    'average_students' => $statsArray[$reasonName]->average_students
                ];
            } else {
                $statsArray[$reasonName] = [
                    'reservation_reason_id' => $reason['reason_id'],
                    'reservation_reason_name' => $reason['reason_name'],
                    'total_reservations' => 0,
                    'average_students' => 0
                ];
            }
        }
        return $statsArray;
    }

    /**
     * Retrieve a list of all reservations classrooms statuses
     * @param array $data
     * @param array $statuses
     * @return array
     */
    private function getClassroomStatsReservations(array $data, array $statuses): array
    {
        $stats = DB::table('classroom_reservation')
            ->join('reservations', 'classroom_reservation.reservation_id', '=', 'reservations.id')
            ->select(
                DB::raw('DATE(reservations.date) as date'),
                DB::raw("SUM(CASE WHEN reservations.reservation_status_id = {$statuses['accepted']} THEN 1 ELSE 0 END) as accepted"),
                DB::raw("SUM(CASE WHEN reservations.reservation_status_id = {$statuses['rejected']} THEN 1 ELSE 0 END) as rejected"),
                DB::raw("SUM(CASE WHEN reservations.reservation_status_id = {$statuses['pending']} THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN reservations.reservation_status_id = {$statuses['cancelled']} THEN 1 ELSE 0 END) as cancelled")
            )
            ->where('classroom_reservation.classroom_id', $data['classroom_id'])
            ->whereBetween('reservations.date', [$data['date_start'], $data['date_end']])
            ->groupBy(DB::raw('DATE(reservations.date)'))
            ->get()
            ->toArray();
        return $stats;
    }
}
