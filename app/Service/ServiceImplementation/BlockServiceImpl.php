<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\{
    BlockRepository,
    BlockLogRepository,
    BlockStatusRepository,
    ReservationStatusRepository as ReservationStatus
};

use App\Models\{
    Block
};

use App\Service\{
    BlockService
};

use Carbon\Carbon;

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

    /**
     * Retrieve a block within its statistics based on reservations by classrooms
     * @param int $block_id
     * @return array
     */
    public function getBlockStatistics(int $block_id): array 
    {
        $result = [
            'accepted' => 0, 
            'rejected' => 0, 
            'cancelled' => 0, 
            'pending' => 0
        ];
        $classrooms = $this->classroomService->getClassroomsByBlock($block_id); 

        $data = [
            'date_start' => Carbon::parse('0001-01-01'),
            'date_end' => Carbon::now()->addMonths(6),
            'classroom_id' => -1,
        ];

        foreach ($classrooms as $classroom) {
            $data['classroom_id'] = $classroom['classroom_id'];
            $chart = $this->classroomService
                ->getClassroomStats($data)['chart'];
            for ($i = 0; $i<count($chart); $i++) {
                $set = (array)$chart[$i];
                $result['accepted'] += $set['accepted'];
                $result['rejected'] += $set['rejected']; 
                $result['cancelled'] += $set['cancelled'];
                $result['pending'] += $set['pending']; 
            }
        }
        $result['total'] = $result['accepted']+$result['rejected']+$result['cancelled']+$result['pending'];
        return array_merge($result, $this->getBlock($block_id));
    }

    /**
     * Find a block by its name
     * @param string $name
     * @return array
     */
    public function findByName(string $name): array 
    {
        return $this->blockRepository->findByName($name);
    }

    /**
     * Register a block enabled by default
     * @param array $data
     * @return string
     */
    public function store(array $data): string 
    {
        $data['block_status_id'] = BlockStatusRepository::enabled(); 
        $this->blockRepository->save($data);
        return 'Se guardo correctamente el nuevo bloque '.$data['block_name'];
    }

    /**
     * Update a single block registered, with the new information
     * @param array $data
     * @param int $block_id
     */
    public function update(array $data, int $blockId): string 
    {
        $block = $this->blockRepository->update($data, $blockId);
        if ($data['block_status_id'] ==  BlockStatusRepository::disabled())
            foreach ($block['block_classrooms'] as $classroom) {
                $this->classroomService->disable($classroom['classroom_id']);
            }
        return 'Se modifico correctamente el bloque '.$block['block_name'];
    }

    /**
     * Delete a block within its id
     * @param int $blockId
     * @return string
     */
    public function delete(int $blockId): string 
    {
        $block = $this->blockRepository->delete($blockId); 
        $classrooms = array_map(
            function ($classroom) 
            {
                return $classroom['classroom_id'];
            },
            $this->classroomService
                ->getClassroomsByBlock($block['block_id'])
        );

        foreach ($classrooms as $classroomId)
            $this->classroomService->deleteByClassroomId($classroomId);
        return 'Se elimino el bloque '.$block['block_name']; 
    }
}