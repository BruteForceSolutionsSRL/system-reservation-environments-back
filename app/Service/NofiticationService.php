<?php
namespace App\Service;

interface NofiticationService
{
    function getNotification(int $id): array;
    function getNotifications(int $personId): array; 
    function store(array $data): array; 
    function update(array $data, int $id): array; 
}

