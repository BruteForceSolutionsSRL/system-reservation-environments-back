<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\ReservationStatusServiceImpl;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
};

use Exception;

class ReservationStatusController extends Controller
{
    private $reservationStatusService; 
    public function __construct()
    {
        $this->reservationStatusService = new ReservationStatusServiceImpl(); 
    }

    /**
     * Retrieve a list of all time-slots
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        try {
            return response()->json(
                $this->reservationStatusService->getAllReservationStatuses(), 
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