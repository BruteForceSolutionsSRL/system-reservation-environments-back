<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

use app\Models\Classroom; 

class ClassroomController extends Controller
{
    /**
     * @param
     *  None
     * @return 
     *  All classrooms
     */
    public function list() 
    {
        $classrooms = Classroom::all();
        $status = 200; 
        return response()->json($classrooms, $status);
    }
    /**
     * @covers: 
     * To retrieve data about a environment
     */
    public function index() 
    {
        $statusCode = 200; 
        $data = ['Hello']; 
    }

    /**
     * @param
     * Request (body): 
     * {
     * 'environment': 'id', 
     * 'quantity': 'number', 
     * 'date': Date, 
     * 'initial period': 'id', 
     * 'final period': 'id'
     * }
     */
    public function store(Request $request) 
    {
        $dataToJSON = null;
        $statusCode = null; 

        try {
            $environmentId = $request->input(('environment'));
            $quantity = $request->input(('quantity'));
            $date = strtotime($request->input(('date'))); 
            $initialPeriod = $request->input(('initial period'));
            $finalPeriod = $request->input(('final period'));

            $environment = null; // this have to be connected with database or ORM to consult it to database


            $ok = 1; 
            // consult to database if its ok : 

            if ($ok!=0) {
                $dataToJSON = ['assigned' => '0']; 
            } else {
                // in this part we just add to the db


                $dataToJSON = ['assigned' => '1']; 
            }
        } catch (Exception $e) {
            $statusCode = 500; 
            $dataToJSON = ['error' => 'Internal Server Error']; 
        }

        // I'm using response with macros, for return JSON objects, and status 

        return response()->json($dataToJSON, $statusCode);
    }

    /**
     * @covers
     * To cancel assigned types. 
     */
    public function update(Request $request, $id) 
    {
    }

    /**
     * @covers
     * IDK
     */
    public function destroy($id) 
    {
    }
}