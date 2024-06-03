<?php

namespace App\Service\ServiceImplementation; 

use App\Service\MailerService;

use Illuminate\Mail\Mailable;

use App\Mail\{
	ClassroomNotificationMailer,
	ReservationNotificationMailer
}; 

class MailerServiceImpl implements MailerService
{
	public function sendMail(Mailable $mail, array $addresses): void
	{
		\Mail::to($addresses)->send($mail);
	}

	public function acceptReservation($data):void 
	{
		echo 'llegue';
		$addresses = []; 
		foreach ($data['to'] as $user) array_push($addresses, $user['person_email']);

		echo 'llego';
		$this->sendMail(
			new ReservationNotificationMailer($data, 2),
			$addresses
		);
	}
}
