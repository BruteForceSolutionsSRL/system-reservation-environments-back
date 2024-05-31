<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\NotificationRepository;
use App\Service\NofiticationService;

class NotificationServiceImpl implements NofiticationService
{
    private $notificationRepository; 

    public function __construct() 
    {
        $this->notificationRepository = new NotificationRepository();
    }

    public function getNotification(int $id): array 
    {
        return $this->notificationRepository->getNotification($id);
    }

    public function getNotifications(int $personId): array
    {
        return $this->notificationRepository->getNotifications($personId);
    }

    public function send(array $data): void 
    {
        $notification = $this->notificationRepository->save($data); 
        // send email controller

        $this->update($notification, $notification['id']);
    }

    public function update(array $data, int $id): void
    {
        $this->notificationRepository->update($data, $id);
    } 
}