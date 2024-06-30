<?php

namespace App\Service\ServiceImplementation; 

use App\Mail\NotificationMail;
use App\Service\MailerService;

use Illuminate\Mail\Mailable;

use App\Jobs\MailSenderJob;

use App\Mail\{
	ClassroomNotificationMailer,
	ReservationNotificationMailer,
	BlockNotificationMailer
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
		MailSenderJob::dispatch($addresses, $mail);//->withTags(['mail']);
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
            'type' => NotificationTypeRepository::informative(),
            'sendBy' => $sender, 
            'to' => [],
			'sended' => 1,
		];
		$this->getPersonsByReservation($emailData, $reservation);

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
	 * @return array 
	 */
	public function acceptReservation(array $reservation, int $sender): array
	{
		$emailData = [
			'title' => 'SOLICITUD DE RESERVA #'.$reservation['reservation_id'].' ACEPTADA', 
            'body' => 'Se acepto la solicitud #'.$reservation['reservation_id'],
            'type' => NotificationTypeRepository::accepted(),
            'sendBy' => $sender, 
			'sended' => 1,
            'to' => []
		];

		$this->getPersonsByReservation($emailData, $reservation);

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
				ReservationStatus::accepted()
			), 
			$addresses
		);
		return $emailData;
	}

	/**
	 * Create a Mailable class with data rejected reservation 
	 * @param array $data
	 * @return array
	 */
	public function rejectReservation(array $reservation, int $sender, string $message): array
	{
		$emailData = [
			'title' => 'SOLICITUD DE RESERVA #'.$reservation['reservation_id'].' RECHAZADA', 
            'body' => 'Se rechazo la solicitud #'.$reservation['reservation_id'].' '.$message,
            'type' => NotificationTypeRepository::rejected(),
            'sendBy' => $sender, 
			'sended' => 1,
            'to' => []
		];

		$this->getPersonsByReservation($emailData, $reservation);

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
				ReservationStatus::rejected()
			), 
			$addresses
		);
		return $emailData;
	}

	/**
	 * Create a Mailable class with data cancelled reservation
	 * @param array $data
	 * @return array 
	 */
	public function cancelReservation(array $reservation, int $sender, string $message): array 
	{
		$emailData = [
			'title' => 'SOLICITUD DE RESERVA #'.$reservation['reservation_id'].' CANCELADA', 
            'body' => 'Se cancelo la solicitud #'.$reservation['reservation_id'].' '.$message,
            'type' => NotificationTypeRepository::cancelled(),
            'sendBy' => $sender, 
			'sended' => 1,
            'to' => []
		];

		$this->getPersonsByReservation($emailData, $reservation);

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
				ReservationStatus::cancelled()
			), 
			$addresses
		);
		return $emailData;
	}

	/**
	 * Notificate a re-assignation for a single reservation
	 * @param array $data
	 * @return void
	 */
	public function reassingReservation(array $reservation, int $sender): array 
	{
		$emailData = [
			'title' => 'CAMBIO DE AMBIENTES PARA LA SOLICITUD DE RESERVA '.$reservation['reservation_id'], 
            'body' => 'Se realizo la reasignacion de ambientes a la solicitud #'.$reservation['reservation_id'].', para mas informacion sobre la razon de cambio contactar con el encargado.',
            'type' => NotificationTypeRepository::warning(),
            'sendBy' => $sender, 
            'to' => [],
			'sended' => 1,
		];

		$this->getPersonsByReservation($emailData, $reservation);

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
				-1
			), 
			$addresses
		);
		return $emailData;
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
	 * Create a Mailable class with data for creation
	 * @param array $data
	 * @return void
	 */
	public function sendCreationBlockEmail(array $data): void 
	{
		$this->sendMail(
			new BlockNotificationMailer($data, 1), 
			$this->getAddresses($data['to'])
		);
	}

	/**
	 * Create a Mailable class with data for update
	 * @param array $data
	 * @return void
	 */
	public function sendUpdateBlockEmail(array $data): void 
	{
		$this->sendMail(
			new BlockNotificationMailer($data, 3), 
			$this->getAddresses($data['to'])
		);
	}

	/**
	 * Create a Mailable class with data for delete
	 * @param array $data
	 * @return void
	 */
	public function sendDeleteBlockEmail(array $data): void 
	{
		$this->sendMail(
			new BlockNotificationMailer($data, 2), 
			$this->getAddresses($data['to'])
		);
	}

	private function getPersonsByReservation(array &$emailData, array $reservation): void 
	{
		for ($i =0 ; $i < count($reservation['groups']); $i++) 
			array_push($emailData['to'], $reservation['groups'][$i]);
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
