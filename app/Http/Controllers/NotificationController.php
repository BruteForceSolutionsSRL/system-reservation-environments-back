<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Person;
use Exception;
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
                    => 'There is no person with this ID'], 404);
        }

        $notificationType = NotificationType::findOrFail(
                $request->notification_type_id);

        if ($notificationType == null) {
            return response()->json(['error'
                    => 'Use a correct notification type'], 500);
        }

        try {
            DB::beginTransaction();

            $notification = Notification::create([
                'title' => $request->title,
                'description' => $request->description,
                'created_at' => now(),
                'readed' => 0,
                'person_id' => $request->person_id,
                'notification_type_id' => $request->notification_type_id
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }

        return response()->json($notification, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification == null) {
            return response()->json(['meesage'
                    => 'No existe una notificacion con el ID'], 404);
        }

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
