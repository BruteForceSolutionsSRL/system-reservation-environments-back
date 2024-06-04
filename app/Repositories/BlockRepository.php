<?php
namespace App\Repositories;

use App\Models\Block; 

use Illuminate\Database\Eloquent\Model;

class BlockRepository
{
    protected $model; 
    public function __construct()
    {
        $this->model = Block::class;
    }
    
    /**
     * Retrieve a list of all blocks Eloquent
     * @param none
     * @return array
     */
    public function getAllBlocks(): array
    {
        return Block::all()->map(
            function ($block) 
            {
                return $this->formatOutput($block); 
            }
        )->toArray();
    }

    /**
     * Retrieve a single block formatted
     * @param int $id
     * @return array
     */
    public function getBlock(int $id): array 
    {
        $block = Block::find($id); 
        if ($block == null) return []; 
        return $this->formatOutput($block);
    }
    
    /**
     * Converts Block to array
     * @param Block $block
     * @return array
     */
    private function formatOutput(Block $block): array 
    {
        if ($block == null) return [];
        return [
            'block_id' => $block->id, 
            'block_name' => $block->name, 
            'block_maxfloor' => $block->max_floor
        ];
    }
}