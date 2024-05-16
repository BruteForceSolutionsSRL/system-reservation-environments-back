<?php
namespace App\Repositories;

use App\Models\ReservationStatus; 

use Illuminate\Cache\Repository; 
use Illuminate\Database\Eloquent\Model; 

class ReservationStatusRepository extends Repository
{
     
    private $model; 
    function __construct(Model $model) 
    {
        $this->model = $model;
    }

    public static function accepted() 
    {   
        return ReservationStatus::where('status', 'ACCEPTED')->get()->pop()->id; 
    }
    public static function rejected() 
    {
        return ReservationStatus::where('status', 'REJECTED')
            ->get()->pop()->id;
    } 
    public static function pending() {
        return ReservationStatus::where('status', 'PENDING')
            ->get()->pop()->id; 
    }
    public static function cancelled() 
    {
        return ReservationStatus::where('status', 'CANCELLED')
            ->get()->pop()->id;
    }
}