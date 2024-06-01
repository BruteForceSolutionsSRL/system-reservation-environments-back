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
        'title' => 'Mail from Codersvibe.com',
        'body' => 'This is my first mail using Gmail SMTP'
    ];
   
    \Mail::to('202103261@est.umss.edu')->send(new \App\Mail\NotificationMail($details));

    dd('Email is sent. BD');
});
