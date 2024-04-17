<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationReasonController extends Controller
{
    public function index()
    {
        $reservation_reasons = [
            'Toma de examen',
            'Presentacion de Tesis',
            'Practicas'
        ];

        return response()->json($reservation_reasons, 200);
    }
}
