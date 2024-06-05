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

    /**
     * Retrieves a list of all time slots 
     * @param none
     * @return array
     */
    public function getAllTimeSlots(): array
    {
        return $this->timeSlotRepository->getAllTimeSlots();
    }
    
    /**
     * Retrieves a single time slot
     * @param int $timeSlotID
     * @return array
     */
    public function getTimeSlot(int $timeSlotID): array
    {
        return $this->timeSlotRepository->getTimeSlotById($timeSlotID);
    }

    /**
     * Sort two timeSlost by its id
     * @param array $timeSlots
     * @return array
     */
    public function getTimeSlotsSorted($timeSlots): array
    {
        $array = array(); 
        foreach ($timeSlots as $timeSlot) 
            array_push($array, $timeSlot->id);
        sort($array); 
        return $array;
    }
}