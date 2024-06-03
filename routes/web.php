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
    return view('welcome');
});

Route::get('/send', function () {
    $details = [
        'title' => 'Mail desde el backend XD',
        'body' => 'Hola este seria mi cuerpo de algun tipo',
        'updated_at' => '02/06/2024', 
        'status' => 'Rechazado' 
    ];
   
    \Mail::to('202103261@est.umss.edu')->send(new \App\Mail\NotificationMail($details, 2));

    dd('Email is sent. BD');
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
