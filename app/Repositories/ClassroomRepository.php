<?php
namespace App\Repositories;

use App\Models\Classroom;

use Illuminate\Cache\Repository;

use App\Repositories\ClassroomStatusRepository as ClassroomStatus;
use SebastianBergmann\Type\VoidType;

class ClassroomRepository extends Repository
{
    protected $model;
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Function to retrieve a list of all classrooms
     * @param int
     * @return array
     */
    public function getAllClassrooms(): array
    {
        return $this->model::where('classroom_status_id',ClassroomStatus::available())
        ->get()
        ->map(
            function ($classroom) {
                return $this->formatOutput($classroom);
            }
        )->toArray();
    }

    /**
     * Retrieves a list of all classrooms with a specified status 
     * @param array $statuses
     * @return array
     */
    public function getClassrooomsByStatus(array $statuses): array
    {

        return $this->model::where(
            function ($query) use ($statuses) 
            {
                foreach ($statuses as $status) 
                    $query->orWhere('classroom_status_id', $status);
            }
        )->get()->map(
            function ($classroom) 
            {
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
        return $this->model::where('classroom_status_id',ClassroomStatus::available())
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
        return $this->model::where('classroom_status_id',ClassroomStatus::available())
            ->where('block_id', $blockId)
            ->get()
            ->map(
                function ($classroom)
                {
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
     * @param string $time
     * @return array
     */
    public function getClassroomById(int $classroomId): array
    {
        $classroom = $this->model::find($classroomId);
        if (($classroom != null) && 
              ($classroom->classroom_status_id === ClassroomStatus::available())
        ) {
            return $this->formatOutput($classroom);
        }
        return []; 
        /*return $this->model::find($classroomId);*/
    }

    /**
     * Function to format a classroom into an array
     * @param Classroom $classroom
     * @return array
     */
    private function formatOutput(Classroom $classroom): array
    {
        return [
            'classroom_id' => $classroom->id,
            'classroom_name' => $classroom->name,
            'classroom_type_id' => $classroom->classroom_type_id, 
            'classroom_status_id' => $classroom->classroom_status_id,
            'capacity' => $classroom->capacity,
            'floor' => $classroom->floor,
            'block_id' => $classroom->block_id
        ];
    }
}

