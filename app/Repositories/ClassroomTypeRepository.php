<?php
namespace App\Repositories;

use App\Models\{
    ClassroomType
}; 
class ClassroomTypeRepository 
{
    protected $model; 
    public function __construct($model)
    {
        $this->model = $model;
    }
    /**
     * Retrieve a list of all classroom types
     * @param none
     * @return array
     */
    public function getAllClassroomTypes(): array
    {
        return $this->model::all()->map(
            function ($classroomType) 
            {
                return $this->formatOutput($classroomType);
            }
        )->toArray();
    }

    /**
     * Format ClassroomType to array
     * @param ClassroomType $classroomType
     * @return array
     */
    private function formatOutput(ClassroomType $classroomType): array
    {
        return [
            'type_id' => $classroomType->id, 
            'type_name' => $classroomType->description
        ];
    }
}