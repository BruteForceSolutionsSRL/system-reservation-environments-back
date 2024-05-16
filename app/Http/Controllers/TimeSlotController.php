<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\TimeSlotServiceImpl;

use Illuminate\Http\JsonResponse as Response;
use Exception;

class TimeSlotController extends Controller
{
    private $timeSlotService; 
    public function __construct()
    {
        $this->timeSlotService = new TimeSlotServiceImpl(); 
    }

    /**
     * Retrieve a list of all time-slots
     * @param none
     * @return Response
     */
    public function list(): Response
    {
        try {
            return response()->json(
                $this->timeSlotService->getAllTimeSlots(), 
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