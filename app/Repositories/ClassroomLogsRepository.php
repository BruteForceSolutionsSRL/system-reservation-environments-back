<?php

namespace App\Repositories;

use App\Models\{
    ClassroomLogs,
};
use DateTime;

class ClassroomLogsRepository
{
    protected $model;
    function __construct($model)
    {
        $this->model = $model;
    }
    /**
     * Function to retrieve the last matching classroom
     * @param DateTime $dateTime
     * @param int $classroomId
     * @return array
     */
    public function retriveLastClassroom(array $data): array
    {
        $classroomLog = $this->model::where('classroom_id', $data['classroom_id'])
            ->where('created_at', '<', $data['date'])
            ->where('classroom_status_name', '!=', 'ELIMINADO')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if(!$classroomLog) {
            return [];
        }

        return $this->formatOutput($classroomLog);
    }

    /**
     * Function to format a classroom into an array
     * @param ClassroomLogs $classroomLogs
     * @return array
     */
    private function formatOutput(ClassroomLogs $classroomLogs): array
    {
        return [
            'classroom_id' => $classroomLogs->classroom_id,
            'name' => $classroomLogs->name,
            'capacity' => $classroomLogs->capacity,
            'floor' => $classroomLogs->floor,
            'block_name' => $classroomLogs->block_name,
            'type_name' => $classroomLogs->classroom_type_name,
            'status_name' => $classroomLogs->classroom_status_name,
            'created_at' => $classroomLogs->created_at,
        ];
    }
}
