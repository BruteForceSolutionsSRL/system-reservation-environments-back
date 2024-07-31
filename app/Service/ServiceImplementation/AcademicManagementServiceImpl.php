<?php
namespace App\Service\ServiceImplementation; 

use App\Service\AcademicManagementService; 

use App\Repositories\AcademicManagementRepository;

class AcademicManagementServiceImpl implements AcademicManagementService
{
	private $academicManagementRepository; 
	
	public function __construct() 
	{
		$this->academicManagementRepository = new AcademicManagementRepository(); 
	}
	
	public function list(): array 
	{
		return $this->academicManagementRepository->list(); 
	}

	public function getAcademicManagement(int $academicManagementId): array 
	{
		return $this->academicManagementRepository
			->getAcademicManagement($academicManagementId); 
	}
	
	public function store(array $data): string 
	{
		$academicManagement = $this->academicManagementRepository->store($data); 
		return 'Se guardo correctamente la gestion academica: '.$academicManagement['name'].'.'; 
	}

	public function update(array $data, int $academicManagementId): string 
	{
		$academicManagement = $this->academicManagementRepository
			->update($data, $academicManagementId); 
		return 'Se actualizo correctamente la gestion academica: '.$academicManagement['name'].'.'; 
	}
}