<?php
namespace App\Repositories; 

use App\Models\{
	Notification
};

use App\Repositories\{
	PersonRepository
};

class NotificationRepository 
{
	protected $model; 

	private $personRepository; 
	private $notificationTypeRepository;

	public function __construct() 
	{
		$this->model = Notification::class;
		
		$this->personRepository = new PersonRepository();
		$this->notificationTypeRepository = new NotificationTypeRepository();
	}

	// aqui falta ver quien es el que esta leendo la notificacion para cambiarla de estado.
	public function getNotification(int $id) 
	{
		return $this->formatOutput($this->model::find($id));
	}

	public function getNotifications(int $personId): array
	{
		return $this->model::whereHas('receptors', 
			function ($query) use ($personId) 
			{
				$query->where('notification_person.person_id', $personId);
			}
		)->get()->map(
			function ($notification) 
			{
				return $this->formatOutput($notification);
			}
		)->toArray();
	}

	public function save(array $data): array
	{
		$notification = new Notification(); 
		$notification->title = $data['title']; 
		$notification->description = $data['body'];
		$notification->notification_type_id = $data['type']; 
		$notification->person_id = $data['sendBy'];

		$notification->save(); 

		$notification->receptors()->attach($data['to']);
		return $this->formatOutput($notification);
	}

	public function update(array $data, int $notificationId): array
	{
		$notification = $this->model::find($notificationId); 
		$notification->title = $data['title']; 
		$notification->description = $data['body'];
		$notification->type = $data['type']; 
		$notification->save();

		return $this->formatOutput($notification);
	} 

	private function formatOutput($notification) 
	{
		if ($notification === null) return [];
		$transmissor = $this->personRepository->getPerson($notification->person_id); 
		$notificationType = $this->notificationTypeRepository->getNotificationType(
			$notification->notification_type_id
		);
		$receptors = $notification->receptors;

		$result = [
			'id' => $notification->id,
			'title' => $notification->title, 
			'type' => $notificationType['notification_type_name'], 
			'sendBy' => $transmissor['person_fullname'], 
			'to' => $receptors->map(
				function ($user) use ($notification)
				{
					$person = \DB::table('notification_person')
						->where('notification_id', $notification->id)
						->where('person_id', $user->id)
						->get()
						->first(); 
					
					return array_merge(
						$this->personRepository->formatOutput($user), 
						['readed' => $person->readed]
					);
				}
			), 
			'body' => $notification->description, 
		];

		if (!in_array($notificationType['notification_type_id'], [
			$this->notificationTypeRepository->accepted(), 
			$this->notificationTypeRepository->cancelled(), 
			$this->notificationTypeRepository->rejected()
		])) {
			$title = $notification->title; 
			$reservationID = '';
			$i = 0; 
			while ($i < strlen($title) && $title{$i}!='#') $i += 1;
			$i+=1;
			while ($i < strlen($title) && $title{$i}>='0' && $title{$i}<='9') {
				$reservationID .= $title{$i}; 
				$i+=1;
			}
			$result['reservation_id'] = intval($reservationID);
		}

		return $result;
	}
}