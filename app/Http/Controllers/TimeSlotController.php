<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TimeSlot;

class TimeSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function list()
    {
        try {
            $timeSlots = [
                ["id"=>1, "time"=>"6:45"], 
                ["id"=>2, "time"=>"7:30"],
                ["id"=>3, "time"=>"8:15"], 
                ["id"=>4, "time"=>"9:00"], 
                ["id"=>5, "time"=>"9:45"], 
                ["id"=>6, "time"=>"10:30"],
                ["id"=>7, "time"=>"11:15"], 
                ["id"=>8, "time"=>"12:00"], 
                ["id"=>9, "time"=>"12:45"], 
                ["id"=>10, "time"=>"13:30"],
                ["id"=>11, "time"=>"14:15"], 
                ["id"=>12, "time"=>"15:00"], 
                ["id"=>13, "time"=>"15:45"], 
                ["id"=>14, "time"=>"16:30"],
                ["id"=>15, "time"=>"17:15"], 
                ["id"=>16, "time"=>"18:00"], 
                ["id"=>17, "time"=>"18:45"], 
                ["id"=>18, "time"=>"19:30"],
                ["id"=>19, "time"=>"20:15"], 
                ["id"=>20, "time"=>"21:00"], 
                ["id"=>21, "time"=>"21:45"],
                ["id"=>22, "time"=>"22:30"]   
            ];
            $result = array();
            foreach($timeSlots as $timeSlot) {
                $item = [
                    "time_slot_id"=>$timeSlot["id"], 
                    "time"=>$timeSlot["time"]
                ];
                array_push($result, $item);
            }
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e -> getMessage()],500);
        }  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
