<?php 
namespace App\Repositories; 

use App\Models\Department; 

class DepartmentRepository
{
	protected $model; 

	public function __construct()
	{
		$this->model = Department::class;
	}

	public function getAllDepartments(): array 
	{
		return $this->model::all()->map(
			function ($department) 
			{
				return $this->formatOutput($department);
			}
		)->toArray();
	}
	public function formatOutput($department) 
	{
		if ($department === null) return  [];
		return [
			'department_id' => $department->id, 
			'name' => $department->name,
		];
	}
}