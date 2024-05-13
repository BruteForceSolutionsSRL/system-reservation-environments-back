<?php

namespace App\Http\Controllers;

use App\Models\ReservationReason;
use Illuminate\Http\Request;

class ReservationReasonController extends Controller
{
    /**
     * @param none
     * @return Request
     */
    public function index()
    {
        $reservationResons = ReservationReason::all()
            ->map(function ($reservationReason) {
                return [
                    'reason_id' => $reservationReason->id,
                    'reason_name' => $reservationReason->reason
                ];
            });

        return response()->json($reservationResons, 200);
    }
}
