<?php

namespace App\Http\Controllers;

use App\Models\ReservationClassroom;
use DateTime;
use Exception;
use Illuminate\Http\Request;

use App\Models\Reservation; 
use App\Models\ReservationStatus;
use App\Models\TimeSlot; 
use App\Models\Classroom; 

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

    /**
     * @description: 
     * Endpoint to assign reservations 
     * if fulfill no overlapping with assigned 
     * reservations.
     */
    public function assign(Request $request) 
    {
        try {

            $reservation = Reservation::findOrFail($request->input('id'));
            if ($reservation==null) {
                return response()
                        ->json(['error'=>'There is no reservation, try it later?'], 404); 
            }
            
            $reservationStatus = $reservation->reservationStatus;
            if ($reservationStatus->status!='pending') {
                return response()
                        ->json(['message'=> 'This reservation was reviewed'], 200);
            }

            $ok = $this->checkAvailibility($reservation); 
            if ($ok==false) {
                return response()
                        ->json(['message'=> 'Already occupied classroom(s)'], 200);
            }

            $acceptedStatus = ReservationStatus::find(1);
            
            $reservation->reservation_status_id = 1;
            $reservation->save();
            return response()->json(['ok'=>1], 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    /**
     * @description: 
     * Function to check availability for all classrooms to do a 
     * reservation in a step 
     */
    private function checkAvailibility($reservation): bool
    {
        try {
            //DB::statement('LOCK TABLE reservations IN SHARE MODE'); 
            $classrooms = $reservation->reservationClassrooms;
            $timeSlots = $reservation->timeSlotReservations; 
            $init = -1; 
            $end = -1;
            foreach ($timeSlots as $iterable) {
                if ($init==-1) $init = $iterable->timeSlot->id;
                else $end = $iterable->timeSlot->id;
            }
            if ($init>$end) {
                $temp = $init; 
                $init = $end; 
                $end = $temp;
            }
            $date = $reservation->date; 
            
            foreach ($classrooms as $iterable) {
                $classroom = $iterable->classroom;
                $reservations = $this->findAcceptedReservationsByClassroomAndDate(
                    $classroom->id, 
                    $date
                );

                $ok = true; 
                foreach($reservations as $reservation) {
                    $left = -1; 
                    $right = -1;
                    $timeSlotReservations = $reservation->timeSlotReservations;
                    foreach ($timeSlotReservations as $timeSlotIterable) {
                        if ($left==-1) $left = $timeSlotIterable->timeSlot->id; 
                        else $right  = $timeSlotIterable->timeSlot->id;
                    } 
                    if ($left>$right) {
                        $temp = $left; 
                        $left = $right; 
                        $right = $temp; 
                    }
                    $ok &= ($right<=$init) || ($left>=$end); 
                }
                if ($ok==false) {
                    return false;                     
                }
            }
            //DB::statement('UNLOCK TABLES');
        } catch (Exception $e) {
            //DB::statement('UNLOCK TABLES');
            return false; 
        }
        return true; 
    }

    /**
     * @description: 
     * Function to retrieve all reservations with equal date and classroom  
     */
    private function findAcceptedReservationsByClassroomAndDate($classroomId, $date) 
    {
        $acceptedStatusId = ReservationStatus::where('status', 'accepted')
                                ->get()
                                ->pop()
                                ->id; 

        $reservationSet = Reservation::with('reservationClassrooms')
                        ->whereHas('reservationClassrooms', 
                            function ($query) use ($classroomId) {
                                $query -> where ('classroom_id', '=', $classroomId);
                            })
                        ->where('reservation_status_id', $acceptedStatusId) 
                        ->where(function($query) use ($date) {
                            $query->where('date', '=', $date)
                                    ->orWhere('repeat', '>', 0);
                        })
                        ->get();
        $result = array();

        foreach ($reservationSet as $reservation) {
            if ($reservation->repeat==0) array_push($result, $reservation);
            else {
                $initialDate = new DateTime($reservation->date);
                $goalDate = new DateTime($date);

                $repeat = $reservation->repeat;
                $difference = $initialDate->diff($goalDate)->days;
                if ($difference%$repeat==0) array_push($result, $reservation);
            }
        }

        return $result;
    }
}
