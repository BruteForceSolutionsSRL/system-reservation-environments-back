<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\{
    BlockRepository
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
    public function getAllBlocks(): array
    {
        return $this->blockRepository->getAllBlocks();
    }
    public function getBlock(int $id): array
    {
        return $this->blockRepository->getBlock($id); 
    }
}