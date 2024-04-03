<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Person;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::all();
        return $notifications;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if (strlen($request->title) == 4) {
            return response()->json(['error'
                    => 'The title must be more than 4 characteres'], 500);
        }

        if (strlen($request->description) >= 10
                and strlen($request->description) <= 40) {
            return response()->json(['error'
                    => 'The title must be more than 4 characteres'], 500);
        }

        $person = Person::findOrFail($request->person_id);

        if ($person == null) {
            return response()->json(['error'
                    => 'There is no person with this ID'], 400);
        }

        $notificationType = NotificationType::findOrFail(
                $request->notification_type_id);

        if ($notificationType == null) {
            return response()->json(['error'
                    => 'Use a correct notification type'], 400);
        }

        $notification = new Notification(); 
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->created_at = date('Y-m-d H:i:s');
        $notification->readed = 0;
        $notification->person_id = $request->person_id;
        $notification->notification_type_id = $request->notification_type_id;
        $notification->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::find($id);
        return $notification;
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
