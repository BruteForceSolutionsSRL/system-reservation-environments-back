<?php 
namespace App\Service\ServiceImplementation;

use App\Service\{
    ReservationReasonService
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
    public function getAllReservationReasons(): array
    {
        return $this->reservationReasonRepository->getAllReservationReason();
    }
}