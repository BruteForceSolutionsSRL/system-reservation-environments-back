<?php
namespace App\Service\ServiceImplementation; 

use App\Service\AcademicPeriodService; 

use App\Repositories\AcademicPeriodRepository;

use Carbon\Carbon;

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

	public function getActualAcademicPeriodByFaculty(int $factultyId): array 
	{
		$now = Carbon::now()->setTimeZone('America/New_York')->format('Y-m-d');
		$result = $this->academicPeriodRepository->getAcademicPeriods(
			[
				'facultyId' => $factultyId, 
				'date' => $now,
			]
		);
		if (empty($result)) {
			return [];
		}
		return $result[0];
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
	// en el data debe llevar el nuevo nombre, y fechas, todo completo, lo que no interesa que me da el anterior son: .
	/**
	 * 1. reservations de configuracion
	 * 2. grupos de materias y profesores posibles + carreras (si es que aplica) 
	 */ 
	public function copyAcademicPeriod(array $data, int $academicPeriodId): string 
	{
		$academicPeriod = $this->academicPeriodRepository->getAcademicPeriod($academicPeriodId);

		return '';
	}
}