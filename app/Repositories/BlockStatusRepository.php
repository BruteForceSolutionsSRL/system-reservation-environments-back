<?php
namespace App\Repositories; 

use App\Models\BlockStatus; 

class BlockStatusRepository 
{
	protected $model; 

	public function __construct()
	{
		$this->model = BlockStatus::class; 
	}

	public static function enabled(): int
	{
		return BlockStatus::where('name', 'HABILITADO')
			->get()->first()
			->id;
	} 

	public static function disabled(): int
	{
		return BlockStatus::where('name', 'DESHABILITADO')
			->get()->first()
			->id;
	} 

	public static function deleted(): int
	{
		return BlockStatus::where('name', 'ELIMINADO')
			->get()->first()
			->id;
	} 

	public function findByName(string $name): array 
	{
		return $this->model::where('name', $name)
			->get()->map(
				function ($blockStatus) 
				{
					return $this->formatOutput($blockStatus);
				}
			)->toArray();
	} 

	public function formatOutput($blockStatus): array 
	{
		return [
			'block_status_id' => $blockStatus->id, 
			'name' => $blockStatus->name,
		];
	}

}