<?php

namespace App\Http\Controllers;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
}; 

use App\Service\ServiceImplementation\{
    PersonServiceImpl
};

class PersonController extends Controller
{
    private $personService; 

    public function __construct()
    {
        $this->personService = new PersonServiceImpl();
    }

    /**
     * Retrieve information about a single user based on its ID
     * @param int $userId
     * @param Request $request
     * @param Response
     */
    public function show(int $userId, Request $request): Response
    {
        try {
            return response()->json(
                $this->personService->getUser($userId),
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
     * Retrieve a response about a list of all persons
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        try {
            return response()->json(
                $this->personService->getAllUsers(),
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
     * Retrieve a response of all list teachers 
     * @param Request $request
     * @return Reponse
     */
    public function listTeachers(Request $request): Response 
    {
        try {
            return response()->json(
                $this->personService->getAllTeachers(),
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