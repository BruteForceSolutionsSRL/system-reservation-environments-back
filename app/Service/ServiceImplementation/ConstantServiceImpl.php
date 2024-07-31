<?php
namespace App\Service\ServiceImplementation; 

use App\Repositories\ConstantRepository;

class ConstantServiceImpl  
{
	private $constantRepository; 

	public function __construct() {
		$this->constantRepository = new ConstantRepository(); 
	}

	public function getAutomaticReservationConstant(): array
	{
		return [
			'status' => $this->constantRepository->getAutomaticReservation()
		]; 
	}

	public function getMaximalReservationPerGroup(): array
	{
		return [
			'status' => $this->constantRepository->getMaximalReservationPerGroup()
		];
	}

	public function updateAutomaticReservation(): void
	{
		$this->constantRepository->updateAutomaticReservation(); 
	}

	public function updateMaximalReservationPerGroup(array &$data): void
	{
		$this->constantRepository->updateMaximalReservationPerGroup($data);
	}

}