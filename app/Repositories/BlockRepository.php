<?php
namespace App\Repositories;

use App\Models\Block; 
class BlockRepository
{
    public function getAllBlocks(): array
    {
        return Block::all()->map(
            function ($block) 
            {
                return $this->formatOutput($block); 
            }
        )->toArray();
    }
    public function getBlock(int $id): array 
    {
        $block = Block::find($id); 
        if ($block == null) return []; 
        return $this->formatOutput($block);
    }
    private function formatOutput(Block $block): array 
    {
        return [
            'block_id' => $block->id, 
            'block_name' => $block->name, 
            'block_maxfloor' => $block->max_floor
        ];
    }
}