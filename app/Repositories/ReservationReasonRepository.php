<?php
namespace App\Repositories;

use App\Models\{
    ReservationReason
};
class ReservationReasonRepository
{
    /**
     * Retrieve a list of all reservation reasons formatted
     * @param none
     * @return array 
     */
    public function getAllReservationReason(): array
    {
        return ReservationReason::all()->map(
            function ($reservationReason) 
            {
                return $this->formatOutput($reservationReason);
            }
        )->toArray();
    }

    /**
     * Transform ReservationReason to array
     * @param ReservationReason $reservationReason
     * @return array
     */
    private function formatOutput(ReservationReason $reservationReason): array
    {
        return [
            'reason_id' => $reservationReason->id,
            'reason_name' => $reservationReason->reason
        ];
    } 
}