<?php
namespace App\Repositories; 

use App\Models\BlockLog;

class BlockLogRepository
{
	protected $model; 

	private $blockStatusRepository; 

	public function __construct() 
	{
		$this->model = BlockLog::class;
		$this->blockStatusRepository = new BlockStatusRepository();
	}

	public function formatOutput($blockLog) 
	{
		if (!$blockLog) return [];
        return [
            'block_id' => $blockLog->block_id, 
            'block_name' => $blockLog->name, 
            'block_maxfloor' => $blockLog->max_floor, 
            'block_maxclassrooms' => $blockLog->max_classrooms,
			'status_name' => $blockLog->block_status_name,
            'block_status_id' => $this->blockStatusRepository->findByName(
            	$blockLog->block_id
            )['id'], 
            'block_status_name' => $blockLog->blockStatus->name,
        ];
	}
}