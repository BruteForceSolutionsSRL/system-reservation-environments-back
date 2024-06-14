<?php 
namespace App\Service\ServiceImplementation;

use App\Service\{
    ReservationReasonService
};

use App\Models\{
    ReservationReason
};

use App\Repositories\{
    ReservationReasonRepository
}; 

class ReservationReasonServiceImpl implements ReservationReasonService
{
    private $reservationReasonRepository; 
    public function __construct()
    {
        $this->reservationReasonRepository = new ReservationReasonRepository();
    }
    
    /**
     * Retrieve a list of all reservation reasons 
     * @param none
     * @return array
     */
    public function getAllReservationReasons(): array
    {
        return $this->reservationReasonRepository->getAllReservationReason();
    }
}