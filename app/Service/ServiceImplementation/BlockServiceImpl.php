<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\{
    BlockRepository,
    BlockLogRepository,
    BlockStatusRepository
};

use App\Models\{
    Block
};

use App\Service\{
    BlockService
};

class BlockServiceImpl implements BlockService
{
    private $blockRepository; 
    private $blockLogRepository; 
    private $blockStatusRepository;

    private $classroomService; 
    public function __construct()
    {
        $this->blockRepository = new BlockRepository();
        $this->blockLogRepository = new BlockLogRepository();
        $this->blockStatusRepository = new BlockStatusRepository();

        $this->classroomService = new ClassroomServiceImpl();
    }

    /**
     * Retrieves a list of all blocks 
     * @param none
     * @return array
     */
    public function getAllBlocks(): array
    {
        return $this->blockRepository->getAllBlocks();
    }
    
    /**
     * Retrieve a single Block using its ID
     * @param int $id
     * @return array
     */
    public function getBlock(int $id): array
    {
        return $this->blockRepository->getBlock($id); 
    }

    public function getBlockStatistics(int $block_id): array 
    {
        return $this->blockRepository->getStatistics($block_id); 
    }

    public function findByName(string $name): array 
    {
        return $this->blockRepository->findByName($name);
    }

    public function store(array $data): string 
    {
        $this->blockRepository->save($data);
        return 'Se guardo correctamente el nuevo bloque '.$data['block_name'];
    }

    public function update(array $data, int $id): string 
    {
        $block = $this->blockRepository->update($data, $id);

        return 'Se modifico correctamente el bloque '.$block['block_name'];
    }

    public function delete(int $id): string 
    {
        $block = $this->blockRepository->delete($id); 
        $classrooms = array_map(
            function ($classroom) 
            {
                return $classroom['classroom_id'];
            },
            $this->classroomService
                ->getClassroomsByBlock($block['block_id'])
        );
        echo serialize($classrooms);
        return 'Se elimino el bloque '.$block['block_name']; 
    }
}