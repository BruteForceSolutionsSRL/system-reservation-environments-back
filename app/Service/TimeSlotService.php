<?php 
namespace App\Service;
interface TimeSlotService
{
    function getTimeSlot(int $timeSlotID): array; 
    function getAllTimeSlots(): array;
}