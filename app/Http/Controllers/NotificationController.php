<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\{
    JsonResponse as Response,
    Request
}; 
use Illuminate\Support\Facades\Validator;

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
     * @param Request $request
     * @return Response
     */
    public function list(int $personId, Request $request): Response
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
     * @param  Request  $request
     * @return Response
     */
    public function store(int $personId, Request $request): Response
    {   
        try {
            $validator = $this->validateNotificationData($request); 
            if ($validator->fails()) {
                $message = implode('.', $validator->errors()->all()); 
                return response()->json(['message' => $message], 400);
            }

            $data = $validator->validated();

            $data['sendBy'] = $personId;
            
            return response()->json(
                $this->notificationService->store($data),
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
     * Validate all atributes whithin Classroom register
     * @param Request $request
     * @return mixed
     */
    private function validateNotificationData(Request $request)
    {
        return Validator::make($request->all(), [
            'title' => '
                required|
                string',
            'body' => '
                required|
                string',
            'type' => '
                required|
                integer|
                exists:notification_types,id',
            'to.*' => '
                required|
                exists:people,id'
        ], [
            'title.required' => 'El atributo \'titulo\' no debe ser nulo o vacio',

            'body.required' => 'El atributo \'cuerpo del mensaje\' no debe ser nulo o vacio',

            'type.required' => 'El atributo \'tipo de mensaje\' no debe ser nulo o vacio',
            'type.integer' => 'El \'tipo de mensaje\' debe ser un valor entero',
            'type.exists' => 'El \'tipo de mensaje\' debe ser una seleccion valida',

            'to.*.required' => 'El atributo \'para\' no debe ser nulo o vacio',
            'to.*.exists' => 'El atributo \'para\' debe ser una seleccion valida de personas',
        ]);
    }
    
    /**
     * Display the specified resource.
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function show(int $personId, int $notificationId,Request $request): Response
    {
        try {
            return response()->json(
                $this->notificationService->getNotification($notificationId, $personId),
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