<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\TimeSlotRepository; 

use App\Service\TimeSlotService;

use App\Models\TimeSlot;
class TimeSlotServiceImpl implements TimeSlotService
{
    private $timeSlotRepository; 
    public function __construct()
    {
        $this->timeSlotRepository  = new TimeSlotRepository(TimeSlot::class);
    }
    public function getAllTimeSlots(): array
    {
        return $this->timeSlotRepository->getAllTimeSlots();
    }
    public function getTimeSlot(int $timeSlotID): array
    {
        return $this->timeSlotRepository->getTimeSlotById($timeSlotID);
    }
}