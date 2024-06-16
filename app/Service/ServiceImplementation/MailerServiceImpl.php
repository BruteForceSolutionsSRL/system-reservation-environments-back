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

use App\Repositories\{
	ReservationStatusRepository as ReservationStatus,
	NotificationTypeRepository
};

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
	 * @return array 
	 */
	public function createReservation(array $reservation, int $sender): array 
	{
		$emailData = [
            'title' => 'SOLICITUD DE RESERVA #'.$reservation['reservation_id'].' PENDIENTE', 
            'body' => 'Se envio la solicitud #'.$reservation['reservation_id'],
            'type' => NotificationTypeRepository::accepted(),
            'sendBy' => $sender, 
            'to' => [],
		];
		for ($i =0 ; $i<count($reservation['groups']); $i++) 
			array_push($emailData['to'], $reservation['groups'][$i]);

        $emailData = array_merge($emailData, $reservation);
		$addresses = $this->getAddresses($emailData['to']);
		$emailData['to'] = array_unique(array_map(
			function ($user) 
			{
				return $user['person_id'];
			},
			$emailData['to']
		));

		$this->sendMail(
			new ReservationNotificationMailer(
				$emailData,
				ReservationStatus::pending()
			), 
			$addresses
		);
		return $emailData;
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
	 * Create a Mailable class with data for creation
	 * @param array $data
	 * @return void
	 */
	public function sendCreationClassroomEmail(array $data): void 
	{
		$this->sendMail(
			new ClassroomNotificationMailer($data, 1), 
			$this->getAddresses($data['to'])
		);
	}

	/**
	 * Create a Mailable class with data for update
	 * @param array $data
	 * @return void
	 */
	public function sendUpdateClassroomEmail(array $data): void 
	{
		$this->sendMail(
			new ClassroomNotificationMailer($data, 3), 
			$this->getAddresses($data['to'])
		);
	}

	/**
	 * Create a Mailable class with data for delete
	 * @param array $data
	 * @return void
	 */
	public function sendDeleteClassroomEmail(array $data): void 
	{
		$this->sendMail(
			new ClassroomNotificationMailer($data, 2), 
			$this->getAddresses($data['to'])
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

		for ($i = 0; $i<count($data); $i++)  
			array_push($addresses, $data[$i]['person_email']);

		return array_unique($addresses); 
	}
}
