<?php
namespace App\Repositories;

use App\Models\{
    ReservationReason
};
class ReservationReasonRepository
{
    public function getAllReservationReason(): array
    {
        return ReservationReason::all()->map(
            function ($reservationReason) 
            {
                return $this->formatOutput($reservationReason);
            }
        )->toArray();
    }
    private function formatOutput(ReservationReason $reservationReason): array
    {
        return [
            'reason_id' => $reservationReason->id,
            'reason_name' => $reservationReason->reason
        ];
    } 
}