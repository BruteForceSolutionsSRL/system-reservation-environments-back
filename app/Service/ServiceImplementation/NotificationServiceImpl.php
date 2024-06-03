<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\NotificationRepository;
use App\Service\NofiticationService;

class NotificationServiceImpl implements NofiticationService
{
    private $notificationRepository;

    private $mailService; 

    public function __construct() 
    {
        $this->notificationRepository = new NotificationRepository();
        
        $this->mailService = new MailerServiceImpl();
    }

    /**
     * Retrieve a single notification within its ID
     * @param int $id
     * @return array
     */
    public function getNotification(int $id): array 
    {
        return $this->notificationRepository->getNotification($id);
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
        $emailData = $this->notificationRepository->save($data);
        if (
            ($emailData['type'] == 'INFORMATIVO') 
            || ($emailData['type'] == 'ADVERTENCIA')
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