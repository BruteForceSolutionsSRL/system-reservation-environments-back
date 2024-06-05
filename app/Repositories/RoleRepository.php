<?php
namespace App\Repositories; 

use App\Models\Role;

class RoleRepository
{
	protected $model; 

	public function __construct()
	{
		$this->model = Role::class;
	}

	public function teacher()
	{
		return $this->model::where('name', 'DOCENTE')
			->get()
			->first()
			->id;
	}
}