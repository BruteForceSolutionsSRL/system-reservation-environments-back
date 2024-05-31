<?php
namespace App\Service;

interface NofiticationService
{
    function getNotification(int $id): array;
    function getNotifications(int $personId): array; 
    function send(array $data): void; 
    function update(array $data, int $id): void; 
}

