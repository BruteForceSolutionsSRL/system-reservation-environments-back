<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\ReservationStatusRepository; 

use App\Models\ReservationStatus;

use App\Service\ReservationStatusService;

class ReservationStatusServiceImpl implements ReservationStatusService
{
    private $reservationStatusRepository; 
    public function __construct()
    {
        $this->reservationStatusRepository  = new ReservationStatusRepository();
    }

    /**
     * Retrieves a list of all reservations statuses 
     * @param none
     * @return array
     */
    public function getAllReservationStatuses(): array
    {
        return $this->reservationStatusRepository->getAllReservationStatuses();
    }
}