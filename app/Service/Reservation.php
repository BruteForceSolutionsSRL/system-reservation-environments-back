<?
use App\Models\Reservation;
interface ReservationService 
{
    /**
     * @param int
     */
    function getReservation($reservationId): Reservation; 
    function getPendingRequest(): array; 
    function listRequestsByTeacher($teacherId): array; 
    function listALlRequestsByTeacher($teacherId): array; 
    function reject($reservationId): bool; 
    function cancel($reservationId): bool; 
    function accept($reservationId): bool; 
    function store($reservationId): bool; 
    //protected function checkAvailibility($reservationId): bool; // borrable 
    function getConflict($reservationId): String; 
}
/**
 * interface List<classroom>: count; 
 */