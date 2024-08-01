<?php
namespace App\Service\ServiceImplementation; 

use App\Service\AcademicPeriodService; 

use App\Repositories\AcademicPeriodRepository;

class AcademicPeriodServiceImpl implements AcademicPeriodService
{
	private $academicPeriodRepository; 
	public function __construct() 
	{
		$this->academicPeriodRepository = new AcademicPeriodRepository();
	}

	public function getAllAcademicPeriods(): array 
	{
		return $this->academicPeriodRepository->getAcademicPeriods([]); 
	} 
	
	public function getAcademicPeriods(array $data): array
	{
		return $this->academicPeriodRepository->getAcademicPeriods(
			$data
		);
	} 

	public function getAcademicPeriod(int $academicPeriodId): array
	{
		return $this->academicPeriodRepository->getAcademicPeriod($academicPeriodId); 
	}
	
	public function store(array $data): string 
	{
		$academicPeriod = $this->academicPeriodRepository->store($data);
		return 'Se registro correctamente el periodo academico '.$academicPeriod['name'].'.';
	}
	
	public function update(array $data, int $academicPeriodId): string
	{
		$academicPeriod = $this->academicPeriodRepository->update($data, $academicPeriodId); 
		return 'Se actualizo correctamente el periodo academico '.$academicPeriod['name'].'.';
	}  

	public function copyAcademicPeriod(array $data): string 
	{
		return '';
	}
}