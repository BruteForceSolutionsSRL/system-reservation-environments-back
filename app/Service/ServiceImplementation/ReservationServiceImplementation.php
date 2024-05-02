<?php 
namespace App\Service\ServiceImplementation;

use App\Service\ReservationService;

use App\Models\{
    ReservationStatus, 
    Reservation
};
class ReservationServiceImplementation implements ReservationService 
{
    private $statusPending;
    private $statusAccepted; 
    private $statusRejected; 
    private $statusExpiredAccepted; 
    private $statusExpiredRejected; 
    private $statusExpiredPending; 
    function __construct()
    {
        $statusExpiredPending = ReservationStatus::where('status', 'EXPIRED PENDING')
                            ->get()
                            ->pop()
                            ->id; 

    }
    public function getReservation($reservationId): Reservation
    {
        return Reservation::find($reservationId); 
    } 

}