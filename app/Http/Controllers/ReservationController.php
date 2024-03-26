<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

use App\Models\Reservation; 
use App\Models\Reservation_Status;
use App\Models\Time_Slot; 

class ReservationController extends Controller
{
    /**
     * index function retrieves all reservations. 
     */
    public function index() 
    {
        return response()->json([]); 
    }
    public function store(Request $request) 
    {
        return response()->json([], 200); 
    }
    public function accept(Request $request) 
    {
        try {

            $reservation = Reservation::findOrFail($request->input('id'));
            if ($reservation==null) {
                return response()->json(['error'=>'There is no reservation, try it later?'], 404); 
            }
            
            $reservation_status = $reservation->status;
            if ($reservation_status->status!='revision') {
                return response()->json(['message'=> 'This reservation was reviewed'], 200);
            }

            
            
            // this function gives me all classrooms required in the reservation 
            // if some other admin tries to accept a reservation have to wait until this one is completed. 
            
            if ($this->checkAvailibility($reservation)==false) {
                return response()->json(['message'=> 'Already occupied classroom(s)'], 200);
            }
            
            $reservation_status = Reservation_Status::get('accepted'); 

            $reservation->reservation_status = $reservation_status->id; 
        } catch (Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 500);
        }
        return response()->json([], 200);
    }

    /**
     * @description: 
     * Function to check availability for all classrooms to do a reservation in a step 
     */
    private function checkAvailibility($reservation): bool
    {
        try {

            $classrooms = $reservation->classrooms;
            $init = Time_Slot::findOrFail($reservation->start_time); 
            $fin = Time_Slot::findOrFail($reservation->end_time);
            
            foreach ($classrooms as $classroom) {
                // how to do it ?
                // optimize?

            }


        } catch (Exception $e) {
            return false; 
        }
        return true; 
    }
}
