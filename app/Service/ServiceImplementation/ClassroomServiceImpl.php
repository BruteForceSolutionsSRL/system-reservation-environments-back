<?php

namespace App\Service\ServiceImplementation;

use App\Service\ClassroomService;

use Illuminate\Support\Facades\DB;

use App\Models\{
    Classroom,
};

use App\Repositories\{
    ClassroomRepository, 
};

class ClassroomServiceImpl implements ClassroomService
{
    private $classroomRepository;
    function __construct()
    {
       $this->classroomRepository = new ClassroomRepository(Classroom::class); 
    }
    /**
     * Retrieve a list of all classrooms
     * @param none
     * @return array
     */
    public function getAllClassrooms(): array
    {
        return $this->classroomRepository->getAllClassrooms();
    }
    
    /**
     * To retrieve array available classrooms within block ID
     * @param int $blockID
     * @return array
     */
    public function availableClassroomsByBlock(int $blockId): array
    {
        return $this->classroomRepository->availableClassroomsByBlock($blockId);
    }

    /**
     * To retrieve all classrooms within block ID
     * @param int $blockID
     * @return array
     */
    public function getClassroomsByBlock(int $blockId): array
    {
        return $this->classroomRepository->getClassroomsByBlock($blockId);
    }

    public function store(array $data): string
    {
        $this->classroomRepository->save($data);
        return "El ambiente fue creado exitosamente.";
    }
}
