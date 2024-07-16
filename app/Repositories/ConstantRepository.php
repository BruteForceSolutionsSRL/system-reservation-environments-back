<?php
namespace App\Repositories;

use App\Models\Constant;

class ConstantRepository
{
    protected $model; 
    
    public function __construct()
    {
        $this->model = new Constant();
    }

    public static function getAutomaticReservation()
    {
        return Constant::where('identifier', 'AUTOMATIC_RESERVATION')
            ->get()->first()->value;
    }
}