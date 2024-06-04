<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\{
    BlockRepository
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
    public function __construct()
    {
        $this->blockRepository = new BlockRepository();
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
}