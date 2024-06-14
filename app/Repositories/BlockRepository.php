<?php
namespace App\Repositories;

use App\Models\Block; 

use Illuminate\Database\Eloquent\Model;

class BlockRepository
{
    protected $model; 

    private $blockStatusRepository; 
    private $classroomRepository; 

    public function __construct()
    {
        $this->model = Block::class;
        $this->blockStatusRepository = new BlockStatusRepository();
        $this->classroomRepository = new ClassroomRepository();
    }
    
    /**
     * Retrieve a list of all blocks Eloquent
     * @param none
     * @return array
     */
    public function getAllBlocks(): array
    {
        return $this->model::where(
            'block_status_id', '!=', $this->blockStatusRepository->deleted()
            )->get()->map(
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
        $block = Block::where('block_status_id', '!=', 
                $this->blockStatusRepository->deleted()
            )->where('id', $id)
            ->get()->first(); 
        if ($block == null) return []; 
        return $this->formatOutput($block);
    }

    public function findByName(string $name): array 
    {
        return $this->model::where('name', $name)
                ->where('block_status_id', '!=', $this->blockStatusRepository->deleted())
                ->get()->map(
                    function ($block) 
                    {
                        return $this->formatOutput($block);
                    }
                )->toArray();
    }

    public function save(array $data): array 
    {
        $block = new Block(); 
        $block->name = $data['block_name'];
        $block->max_floor = $data['block_maxfloor']; 
        $block->max_classrooms = $data['block_maxclassrooms']; 
        $block->block_status_id = $data['block_status_id']; 

        $block->save();
        return $this->formatOutput($block); 
    }

    public function update(array $data, int $id): array 
    {
        $block = $this->model::find($id); 
        if (!$block) return [];
        $block->max_floor = $data['block_maxfloor']; 
        $block->max_classrooms = $data['block_maxclassrooms']; 
        $block->block_status_id = $data['block_status_id']; 

        $block->save();
        return $this->formatOutput($block);
    }

    public function delete(int $id): array 
    {
        $block = $this->model::find($id); 

        $block->block_status_id = $this->blockStatusRepository->deleted();
        $block->save();
        return $this->formatOutput($block);
    }

    /**
     * Converts Block to array
     * @param Block $block
     * @return array
     */
    private function formatOutput($block): array 
    {
        if ($block == null) return [];
        return [
            'block_id' => $block->id, 
            'block_name' => $block->name, 
            'block_maxfloor' => $block->max_floor, 
            'block_maxclassrooms' => $block->max_classrooms,
            'block_status_id' => $block->block_status_id, 
            'block_status_name' => $block->blockStatus->name,
            'block_classrooms' => $this->classroomRepository->getClassroomsByBlock($block->id)
        ];
    }
}