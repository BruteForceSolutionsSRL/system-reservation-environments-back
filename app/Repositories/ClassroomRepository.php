<?php
namespace App\Repositories;

use App\Models\Classroom;

use Illuminate\Cache\Repository;

use App\Repositories\ClassroomStatusRepository as ClassroomStatus;

class ClassroomRepository extends Repository
{
    protected $model;
    function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Function to retrieve a list of all classrooms
     * @param int
     * @return array
     */
    function getAllClassrooms(): array
    {
        return $this->model::select(
            'id',
            'name',
            'capacity',
            'floor'
        )
        ->where('classroom_status_id',ClassroomStatus::available())
        ->get()
        ->map(
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
    function availableClassroomsByBlock(int $blockId): array
    {
        return $this->model::select('id', 'name', 'capacity', 'floor')
                ->where('classroom_status_id',ClassroomStatus::available())
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
    function getClassroomsByBlock(int $blockId): array
    {
        return $this->model::select('id', 'name', 'capacity', 'floor')
            ->where('classroom_status_id',ClassroomStatus::available())
            ->where('block_id', $blockId)
            ->get()
            ->map(function ($classroom){
                return $this->formatOutput($classroom);
            }
        )->toArray();
    }
    
    /**
     * Function to save data of classroom
     * @param array $data
     * @return Classroom
     */
    function save(array $data): Classroom 
    {
        $classroom = new Classroom();
        $classroom->name = $data['classroom_name']; 
        $classroom->capacity = $data['capacity'];
        $classroom->floor = $data['floor_number']; 
        $classroom->block_id = $data['block_id']; 
        $classroom->classroom_type_id = $data['type_id'];
        $classroom->classroom_status_id = 1; 
        $classroom->save();    
        return $classroom;
    }

    /**
     * Function to retrieve a classroom by ID
     * @param int $classroomId
     * @return Classroom
     */
    function getClassroomById(int $classroomId)
    {
        /* $classroom = $this->model::find($classroomId);
        if ($classroom && ($classroom->classroom_status_id === ClassroomStatus::available())) {
            return $classroom;
        }
        return null; */
        return $this->model::find($classroomId);
    }

    /**
     * Function to format a classroom into an array
     * @param Classroom $classroom
     * @return array
     */
    function formatOutput(Classroom $classroom): array
    {
        return [
            'classroom_id' => $classroom->id,
            'classroom_name' => $classroom->name,
            'capacity' => $classroom->capacity,
            'floor' => $classroom->floor,
        ];
    }
}

