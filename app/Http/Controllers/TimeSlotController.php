<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TimeSlot;

class TimeSlotController extends Controller
{
    /**
     * Retrieve a list of all time-slots
     * @return \Response
     */
    public function list()
    {
        try {
            $timeSlots = TimeSlot::select('time', 'id')
                ->get()
                ->map(function ($timeSlot) {
                    return [
                        'time_slot_id' => $timeSlot->id,
                        'time' => $timeSlot->time,
                    ];
                });
            return response()->json($timeSlots, 200);
        } catch (\Exception $e) {
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
