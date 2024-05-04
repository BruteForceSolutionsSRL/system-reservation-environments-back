<?php
namespace App\Repositories;

use App\Models\ReservationStatus; 

use Illuminate\Cache\Repository; 
use Illuminate\Database\Eloquent\Model; 

class ReservationStatusRepository extends Repository
{
    public static $accepted = ReservationStatus::where('status', 'ACCEPTED')
        ->get()->pop()->id; 
    public static $rejected = ReservationStatus::where('status', 'REJECTED')
        ->get()->pop()->id; 
    public static $pending = ReservationStatus::where('status', 'PENDING')
        ->get()->pop()->id; 
    public static $cancelled = ReservationStatus::where('status', 'CANCELLED')
        ->get()->pop()->id; 


    private $model; 
    function __construct(Model $model) 
    {
        $this->model = $model;
    }

}