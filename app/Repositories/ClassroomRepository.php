<?php
namespace App\Repositories;

use App\Models\Classroom;

use Illuminate\Cache\Repository;

class ClassroomRepository extends Repository
{
    protected $model;
    function __construct($model)
    {
        $this->model = $model;
    }
    
    function getAllClassrooms()
    {
        return $this->model::select(
            'id',
            'name',
            'capacity',
            'floor'
        )->get()->map(
            function ($classroom) {
                return $this->formatOutput($classroom);
            }
        )->toArray();
    }

    function availableClassroomsByBlock($blockId)
    {
        return $this->model::select('id', 'name', 'capacity', 'floor')
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

    function getClassroomsByBlock($blockId)
    {
        return $this->model::select('id', 'name', 'capacity', 'floor')
            ->where('block_id', $blockId)
            ->get()
            ->map(function ($classroom){
                return $this->formatOutput($classroom);
            }
        )->toArray();
    }

    private function formatOutput($classroom)
    {
        return [
            'classroom_id' => $classroom->id,
            'classroom_name' => $classroom->name,
            'capacity' => $classroom->capacity,
            'floor' => $classroom->floor,
        ];
    }

    function save(array $data): Classroom 
    {
        $classroom = new Classroom();
        $classroom->name = $data['classroom_name']; 
        $classroom->capacity = $data['capacity'];
        $classroom->floor = $data['floor_number']; 
        $classroom->block_id = $data['block_id']; 
        $classroom->classroom_type_id = $data['type_id']; 
        $classroom->save();    
        return $classroom;
    }

    function getClassroomById($classroomId){
        return $this->model::findOrFail($classroomId)->toArray();
    }
}

