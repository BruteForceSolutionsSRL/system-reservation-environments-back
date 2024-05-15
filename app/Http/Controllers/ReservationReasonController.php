<?php

namespace App\Http\Controllers;

use App\Models\ReservationReason;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response; 

class ReservationReasonController extends Controller
{
    /**
     * @param none
     * @return Response
     */
    public function index(): Response
    {
        try {
            $reservationResons = ReservationReason::all()
            ->map(function ($reservationReason) {
                return [
                    'reason_id' => $reservationReason->id,
                    'reason_name' => $reservationReason->reason
                ];
            });

            return response()->json($reservationResons, 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor', 
                    'error' => $e->getMessage()
                ], 
                500
            );

        }
    }
}
