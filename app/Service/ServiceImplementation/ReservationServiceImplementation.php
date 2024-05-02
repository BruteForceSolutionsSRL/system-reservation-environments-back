<? 
use App\Service\ReservationService;
use App\Models\Reservation; 
class ReservationServiceImplementation extends ReservationService 
{
    public function getReservation($reservationId): Reservation
    {
        return Reservation::find($reservationId); 
    } 

}