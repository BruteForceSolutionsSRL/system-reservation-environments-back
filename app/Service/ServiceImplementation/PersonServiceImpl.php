<?php
namespace App\Service\ServiceImplementation; 

use App\Service\PersonService; 

use App\Repositories\PersonRepository; 

class PersonServiceImpl implements PersonService 
{
	private $personRepository; 

	public function __construct() 
	{
		$this->personRepository = new PersonRepository();
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
}