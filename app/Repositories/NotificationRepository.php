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

	public function getNotification(int $id) 
	{
		return $this->formatOutput($this->model::find($id));
	}

	public function getNotifications(int $personId): array
	{
		return $this->model::with([
            'receptors:id,person_id,notification_id'
        ])->whereHas('receptors', 
			function ($query) use ($personId) 
			{
				$query->where('person_id', $personId);
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
		$notification->type = $data['type']; 

		$transmitter = $this->personRepository->getPerson($data['quien envia']);

		$notification->save(); 

		$notification->receptors()->attach($data['a quien ids']);

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
		
		return [
			'id' => $notification->id,
			'title' => $notification->title, 
			'type' => $notificationType['notification_type_name'], 
			'sendBy' => $transmissor['person_fullname'], 
			'to' => 'Todos', 
			'body' => $notification->description, 
			'readed' => $notification->readed
		];
	}
}