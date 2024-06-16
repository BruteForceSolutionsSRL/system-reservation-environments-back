<?php
namespace App\Service\ServiceImplementation; 

use App\Service\PersonService; 

use App\Repositories\{
	PersonRepository,
	RoleRepository
}; 

class PersonServiceImpl implements PersonService 
{
	private $personRepository; 

	private $roleRepository;

	public function __construct() 
	{
		$this->personRepository = new PersonRepository();
		$this->roleRepository = new RoleRepository();
	}

	/**
	 * Retrieve a single User within its ID
	 * @param int $id
	 * @return array
	 */
	public function getUser(int $id): array
	{
		return $this->personRepository->getPerson($id);
	}

	/**
	 * Retrieve a list of all users registed 
	 * @param none
	 * @return array
	 */
	public function getAllUsers(): array
	{
		return $this->personRepository->getAllPersons(); 
	}

	/**
	 * Retrieve all teachers 
	 * @param none
	 * @return array
	 */
	public function getAllTeachers(): array 
	{
		return $this->personRepository->getUsersByRole(
			[
				$this->roleRepository->teacher()
			]
		);
	}

	/**
	 * Retrieve if a person have permission
	 * @param array $data
	 * @return bool
	 */
	public function havePermission(array $data):bool 
	{
		return $this->personRepository->havePermission($data);;
	}
}