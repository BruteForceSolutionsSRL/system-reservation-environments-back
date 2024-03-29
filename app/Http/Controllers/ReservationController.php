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

    public function ayuda(Request $request) 
    {
        $id = $request->input('id'); 
        $id = 1;
        $reservation = Reservation::find($id);
        $horarios = $reservation->timeSlotReservations;
        $piv = -1; 
        $otro = -1;
        foreach ($horarios as $horario) {
            if ($piv==-1) $piv = $horario->timeSlot->id; 
            else $otro = $horario->timeSlot->id;
        }

        $classrooms = $reservation->reservationClassrooms;

        $classroomId = 3; 
        $date = '2024-03-29';

        $result = $this->findAcceptedReservationsByClassroomAndDate(3, '2024-03-29');         
        $reservationStatuses = ReservationStatus::all();
        $reservationStatus = 0;
        foreach($reservationStatuses as $reservationStatusIterator) {
            if ($reservationStatusIterator->status=='accepted') 
                $reservation_status = $reservationStatusIterator->id;
        } 

        return response()->json([$result, $reservationStatus], 200);
    }
    public function accept(Request $request) 
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
                return response()->json(['message'=> 'Already occupied classroom(s)'], 200);
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
     * Function to check availability for all classrooms to do a reservation in a step 
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
                    foreach ($reservation->timeSlotReservations as $timeSlotIterable) {
                        if ($left==-1) $left = $timeSlotIterable->timeSlot->id; 
                        else $right  = $timeSlotIterable->timeSlot->id;
                    } 
                    if ($left>$right) {
                        $temp = $left; 
                        $left = $right; 
                        $right = $temp; 
                    }
                    $ok &= ($right>$init) || ($left>$end); 
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

    protected $sql = ''; 
    private function findAcceptedReservationsByClassroom(Classroom $classroom) 
    {
        $classId = $classroom->id;
        $result = Reservation::with('timeslots', 'classrooms', 'status', 'group')
        ->whereHas('classrooms', function ($query) use ($classId) {
            $query->where('classroom.id', $classId);
        })
        ->where('reservation_status.value', 'accepted')
        ->get()
        ->toArray();
    
        return $result;
    }
    /**
     * @description: 
     * Function to retrieve all reservations with equal date and classroom  
     */
    private function findAcceptedReservationsByClassroomAndDate($classroomId, $date)
    {
        $result = array(); 

        $reservations = Reservation::all();
        $goalDate = new DateTime($date);
        
        foreach ($reservations as $reservation) {
            $reservationStatus = $reservation->reservationStatus->status;
            if ($reservationStatus!='accepted') continue;

            $classroomBelong = false; 
            $classrooms = $reservation->reservationClassrooms;
            foreach ($classrooms as $classroom) 
                $classroomBelong |= ($classroom->classroom_id)==$classroomId;

            if ($classroomBelong==false) continue;

            $reservationDate = $reservation->date;
            $reservationRepeat = $reservation->repeat;
            if ($reservationDate!=$date && $reservationRepeat<=0) continue;

            if ($reservation->repeat>0) {
                $initialDate = new DateTime($reservation->date);
                if ($initialDate>$goalDate) continue;
                
                $repeat = $reservation->repeat;
                $diff = $initialDate->diff($goalDate)->days; 
                if ($diff%$repeat==0) array_push($result, $reservation);
            } else array_push($result, $reservation); 
        }

        return $result;
    }
}
