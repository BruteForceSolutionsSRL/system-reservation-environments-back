<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('mail/templates/reservation/accept');
});

Route::get('/send', function () {
    $details = [
        'details' => [
            'title' => 'Mail desde el backend XD',
            'body' => 'Hola este seria mi cuerpo de algun tipo',
            'updated_at' => '02/06/2024', 
            'status' => 'Rechazado', 
            'sendBy' => 'SISTEMA',
            'subject_name' => 'ALGEBRA I',
            'block_name' => 'EDIFICIO ACADEMICO 2',
            'quantity' => '100', 
            'reason_name' => 'CLASES',
            'groups' => [
                [
                    'teacher_name' => 'LETICIA BLANCO',
                    'group' => 2
                ],
                [
                    'teacher_name' => 'LETICIA BLANCO',
                    'group' => 3
                ],
                [
                    'teacher_name' => 'LETICIA BLANCO',
                    'group' => 5
                ],
                [
                    'teacher_name' => 'ROSEMARY TORRICO',
                    'group' => 1
                ],
            ],
            'classrooms' => [
                [
                    'classroom_name' => '690A',
                    'capacity' => 50
                ],
                [
                    'classroom_name' => '690D',
                    'capacity' => 25
                ],
                [
                    'classroom_name' => '690E',
                    'capacity' => 25
                ],
            ],
            'date' => '04/06/2024',
            'time_slot' => ['09:45:00', '11:15:00'],
        ],
    ];
    return view('mail/templates/notification', $details);    
});

Route::get('/test', function ()
{
    $details = [
        'title' => 'ESTE ES MI TITULO', 
        'status' => 'Rechazado', 
        'updated_at' => '12/05/2023', 
        'body' => 'este es mi body'
    ];
    return view('mail/templates/templateSolicitudRechazo', $details);
});
