<?php

namespace App\Service\ServiceImplementation; 

use App\Mail\NotificationMail;
use App\Service\MailerService;

use Illuminate\Mail\Mailable;

use App\Mail\{
	ClassroomNotificationMailer,
	ReservationNotificationMailer
}; 

use App\Repositories\ReservationStatusRepository as ReservationStatus;

class MailerServiceImpl implements MailerService
{
	public function sendMail(Mailable $mail, array $addresses): void
	{
		$promesa = new \React\Promise\Promise(
			function () use ($mail, $addresses)
			{
				dd('se esta enviando');
				\Mail::to($addresses)->send($mail);
			}
		);
		$promesa->then(function () {
			dd('TERMINE');
		});
	}

	public function createReservation($data): void 
	{
		$addresses = $this->getAddresses($data['to']); 
		$this->sendMail(
			new ReservationNotificationMailer(
				$data,
				ReservationStatus::pending()
			), 
			$addresses
		);
	}

	public function acceptReservation($data):void 
	{
		$addresses = $this->getAddresses($data['to']); 
		$this->sendMail(
			new ReservationNotificationMailer(
				$data, 
				ReservationStatus::accepted()
			),
			$addresses
		);
	}

	public function rejectReservation($data): void
	{
		$addresses = $this->getAddresses($data['to']); 
		$this->sendMail(
			new ReservationNotificationMailer(
				$data, 
				ReservationStatus::rejected()
			),
			$addresses
		);
	}

	public function cancelReservation($data): void 
	{
		$addresses = $this->getAddresses($data['to']);
		$this->sendMail(
			new ReservationNotificationMailer(
				$data, 
				ReservationStatus::cancelled()
			), 
			$addresses
		);
	}

	public function sendSimpleEmail($data): void 
	{
		$addresses = $this->getAddresses($data['to']);
		$this->sendMail(
			new NotificationMail($data), 
			$addresses
		);
	}

	private function getAddresses($data): array
	{
		$addresses = []; 

		foreach ($data as $user) 
			array_push($addresses, $user['person_email']);

		return $addresses; 
	}
}
