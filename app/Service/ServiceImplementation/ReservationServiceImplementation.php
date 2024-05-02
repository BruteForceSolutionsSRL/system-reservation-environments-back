<? 
namespace App\Service\ServiceImplementation;

use App\Service\ReservationService;

use App\Models\Reservation; 
class ReservationServiceImplementation implements ReservationService 
{
    public function getReservation($reservationId): Reservation
    {
        return Reservation::find($reservationId); 
    } 

}