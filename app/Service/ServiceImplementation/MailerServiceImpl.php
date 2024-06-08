<?php

namespace App\Service\ServiceImplementation; 

use App\Mail\NotificationMail;
use App\Service\MailerService;

use Illuminate\Mail\Mailable;

use App\Jobs\MailSenderJob;

use App\Mail\{
	ClassroomNotificationMailer,
	ReservationNotificationMailer
}; 

use App\Repositories\ReservationStatusRepository as ReservationStatus;

class MailerServiceImpl implements MailerService
{
	/**
	 * Queues a mail to send to all addresses
	 * @param Mailable $mail
	 * @param array $addresses
	 * @return void
	 */
	public function sendMail(Mailable $mail, array $addresses): void
	{
		MailSenderJob::dispatch($addresses, $mail);
		//\Mail::to($addresses)->send($mail);
	}

	/**
	 * Create a Mailable class with data reservation pending
	 * @param array $data
	 * @return void 
	 */
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

	/**
	 * Create a Mailable class with data accepted reservation
	 * @param array $data
	 * @return void 
	 */
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

	/**
	 * Create a Mailable class with data rejected reservation 
	 * @param array $data
	 * @return void 
	 */
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

	/**
	 * Create a Mailable class with data cancelled reservation
	 * @param array $data
	 * @return void 
	 */
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

	/**
	 * Create a Mailable class with data 
	 * @param array $data
	 * @return void 
	 */
	public function sendSimpleEmail($data): void 
	{
		$addresses = $this->getAddresses($data['to']);
		$this->sendMail(
			new NotificationMail($data), 
			$addresses
		);
	}

	/**
	 * Retrieve a list of addresses 
	 * @param array $data
	 * @return array
	 */
	private function getAddresses($data): array
	{
		$addresses = []; 

		foreach ($data as $user) 
			array_push($addresses, $user['person_email']);

		return $addresses; 
	}
}
