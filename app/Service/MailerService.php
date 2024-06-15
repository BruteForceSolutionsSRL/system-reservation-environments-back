<?php
namespace App\Service; 

use Illuminate\Mail\Mailable;

use App\Mail\{
	ClassroomNotificationMailer, 
	ReservationNotificationMailer
};

interface MailerService 
{
	function sendMail(Mailable $mail, array $addresses): void;
}