<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\ReservationReasonServiceImpl;

use Exception;
use Illuminate\Http\JsonResponse as Response; 

class ReservationReasonController extends Controller
{
    private $reservationReasonService;
    public function __construct()
    {
        $this->reservationReasonService = new ReservationReasonServiceImpl();
    }

    /**
     * Retrieve a JSON of all reservation reasons
     * @param none
     * @return Response
     */
    public function list(): Response
    {
        try {
            return response()->json(
                $this->reservationReasonService->getAllReservationReasons(), 
                200
            );
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
