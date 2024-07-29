<?php
namespace App\Service\ServiceImplementation; 

use App\Service\FacultyService;

use App\Repositories\{
	FacultyRepository,
	PersonRepository
}; 

use Carbon\Carbon;

class FacultyServiceImpl implements FacultyService
{
	private $facultyRepository; 
	private $personRepository; 

	public function __construct() {
		$this->facultyRepository = new FacultyRepository(); 
		$this->personRepository = new PersonRepository();
	}

	public function getAllFaculties(): array 
	{
		return $this->facultyRepository->getAllFaculties(); 
	}

	public function getAllFacultiesByUser(int $personId): array 
	{
		$now = Carbon::now()->format('Y-m-d'); 
		return $this->facultyRepository->getAllFacultiesByUser($personId, $now);
	}

}