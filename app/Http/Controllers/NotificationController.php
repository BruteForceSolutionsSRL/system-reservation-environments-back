<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response; 

use App\Service\ServiceImplementation\{
    NotificationServiceImpl
};

class NotificationController extends Controller
{
    private $notificationService; 

    public function __construct()
    {
        $this->notificationService = new NotificationServiceImpl();
    }
    
    /**
     * Display a listing of the resource.
     * @param int $personId
     * @return Response
     */
    public function list($personId): Response
    {
        try {
            return response()->json(
                $this->notificationService->getNotifications($personId),
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request): Response
    {   
        try {
            return response()->json(200);
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
    
    /**
     * Display the specified resource.
     * @param  int  $id
     * @return Response
     */
    public function show(int $personId, int $notificationId): Response
    {
        try {
            return response()->json(
                $this->notificationService->getNotification($notificationId),
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
