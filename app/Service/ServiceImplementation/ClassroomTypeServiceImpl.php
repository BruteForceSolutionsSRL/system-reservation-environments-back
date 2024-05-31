<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\ClassroomTypeRepository;

use App\Service\ClassroomTypeService;

use App\Models\{
    ClassroomType
};

class ClassroomTypeServiceImpl implements ClassroomTypeService
{
    private $classroomTypeRepository; 
    public function __construct()
    {
        $this->classroomTypeRepository = new ClassroomTypeRepository();
    }
    
    /**
     * Retrieve a list of all classroom types
     * @param none
     * @return array
     */
    public function getAllClassroomType(): array
    {
        return $this->classroomTypeRepository->getAllClassroomTypes();
    }
}