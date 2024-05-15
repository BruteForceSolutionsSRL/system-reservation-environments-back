<?php
namespace App\Repositories;

use App\Models\{
    ClassroomType
}; 
class ClassroomTypeRepository 
{
    public function getAllClassroomTypes(): array
    {
        return ClassroomType::all()->map(
            function ($classroomType) 
            {
                return $this->formatOutput($classroomType);
            }
        )->toArray();
    }
    private function formatOutput(ClassroomType $classroomType): array
    {
        return [
            'type_id' => $classroomType->id, 
            'type_name' => $classroomType->description
        ];
    }
}