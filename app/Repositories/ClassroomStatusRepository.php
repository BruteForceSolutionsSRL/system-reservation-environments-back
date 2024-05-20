<?php
namespace App\Repositories;

use App\Models\ClassroomStatus; 

use Illuminate\Cache\Repository; 
use Illuminate\Database\Eloquent\Model; 

class ClassroomStatusRepository extends Repository
{
     
    protected $model; 
    function __construct($model) 
    {
        $this->model = $model;
    }

    public static function available() 
    {   
        return ClassroomStatus::where('name', 'HABILITADO')
            ->get()->pop()->id; 
    }
    public static function disabled() 
    {
        return ClassroomStatus::where('name', 'DESABILITADO')
            ->get()->pop()->id;
    } 
    public static function deleted() 
    {
        return ClassroomStatus::where('name', 'ELIMINADO')
            ->get()->pop()->id; 
    }

    /**
     * Retrieve a list of all statuses for classroom except delete 
     * @param none
     * @return array
     */
    public function getStatuses(): array
    {
        return $this->model::where('id', '!=', $this->deleted())
            ->get()
            ->map(
                function ($classroomStatus) 
                {
                    return $this->formatOutput($classroomStatus); 
                }
            )->toArray();
    }
    
    /**
     * Transform ClassroomStatus to array
     * @param ClassroomStatus $classroomStatus
     * @return array
     */
    private function formatOutput(ClassroomStatus $classroomStatus): array
    {
        if ($classroomStatus == null) return [];
        return [
            'classroom_status_name' => $classroomStatus->name, 
            'classroom_status_id' => $classroomStatus->id
        ];
    }
}