<?php
namespace App\Repositories;

use App\Models\TimeSlot; 

use Illuminate\Cache\Repository; 

class TimeSlotRepository extends Repository
{
    protected $model;
    function __construct($model)
    {
        $this->model = $model;
    }

    function getTimeSlotById(int $timeSlotId): TimeSlot
    {
        return $this->model::find($timeSlotId);
    }
}