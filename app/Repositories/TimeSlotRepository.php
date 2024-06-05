<?php
namespace App\Repositories;

use App\Models\TimeSlot; 

use Illuminate\Cache\Repository; 

class TimeSlotRepository extends Repository
{
    protected $model;
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve TimeSlot by its id
     * @param int $timeSlotId
     * @return array
     */
    public function getTimeSlotById(int $timeSlotId): array
    {
        return $this->formatOutput($this->model::find($timeSlotId));
    }

    /**
     * Retrieve a list of all TimeSlots
     * @param none
     * @return array
     */
    public function getAllTimeSlots(): array
    {
        return $this->model::select('time', 'id')
                ->get()->map(
                    function ($timeSlot) 
                    {
                        return $this->formatOutput($timeSlot);
                    }
                )->toArray();

    }

    /**
     * Transform TimeSlot to array
     * @param TimeSlot $timeSlot
     * @return array
     */
    private function formatOutput(TimeSlot $timeSlot): array
    {
        return [
            'time_slot_id' => $timeSlot->id,
            'time' => $timeSlot->time,
        ];
    }
}