<?php 
namespace App\Service\ServiceImplementation;

use App\Service\ReservationsAssignerService;

use App\Jobs\ReassignerReservationsJob;

class ReservationsAssignerServiceImpl implements ReservationsAssignerService
{
    /**
     * Function to open a new thread and re-assign classrooms to reservations passed in order 
     * @param array $reservation
     * @return void
     */
    public function reassign(array $reservations): void 
    {
        ReassignerReservationsJob::dispatch($reservations);
    }
}