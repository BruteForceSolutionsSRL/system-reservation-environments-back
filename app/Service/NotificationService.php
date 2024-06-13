<?php
namespace App\Service;

interface NotificationService
{
    function getNotification(int $id, int $personId): array;
    function getNotifications(int $personId): array; 
    function store(array $data): array; 
    function update(array $data, int $id): array; 
}

