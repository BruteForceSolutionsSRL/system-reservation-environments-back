<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\NotificationRepository;
use App\Service\NotificationService;

class NotificationServiceImpl implements NotificationService
{
    private $notificationRepository;

    private $mailService; 
    private $personService; 

    public function __construct() 
    {
        $this->notificationRepository = new NotificationRepository();
        
        $this->mailService = new MailerServiceImpl();
        $this->personService = new PersonServiceImpl();
    }

    /**
     * Retrieve a single notification within its ID
     * @param int $id
     * @return array
     */
    public function getNotification(int $id, int $personId): array 
    {
        return $this->notificationRepository->getNotification($id, $personId);
    }

    /**
     * Retrieve a list of all notifications based on which user 
     * @param int $personId
     * @return array
     */
    public function getNotifications(int $personId): array
    {
        return $this->notificationRepository->getNotifications($personId);
    }

    /**
     * Store a new notification
     * @param array $data
     * @return array
     */
    public function store(array $data): array 
    {
        if (!empty($data['to']) && $data['to'][0] == 'TODOS') {
            $users = $this->personService->getAllUsers();
            $data['to'] = [];
            foreach ($users as $user)
                if ($user['person_id'] != $data['sendBy'])
                    array_push($data['to'], $user['person_id']);
        }
        $emailData = $this->notificationRepository->save($data);
        if (
            (($emailData['type'] == 'INFORMATIVO') 
                        || ($emailData['type'] == 'ADVERTENCIA'))
            && (!array_key_exists('sended', $data))
        ) {
            $this->mailService->sendSimpleEmail($emailData);
        }
        return  $emailData;
    }

    /**
     * Update a notification based on its ID
     * @param array $data
     * @param int $id
     * @return array
     */
    public function update(array $data, int $id): array
    {
        $emailData = $this->notificationRepository->update($data, $id);
        $this->mailService->sendSimpleEmail($emailData);
        return $emailData;
    } 
}