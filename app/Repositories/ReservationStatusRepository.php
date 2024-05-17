<?php
namespace App\Repositories;

use App\Models\ReservationStatus; 

use Illuminate\Cache\Repository; 
use Illuminate\Database\Eloquent\Model; 

class ReservationStatusRepository extends Repository
{
     
    protected $model; 
    function __construct(Model $model) 
    {
        $this->model = $model;
    }

    /**
     * Retrieve accepted reservation status id
     * @param none
     * @return int
     */
    public static function accepted(): int 
    {   
        return ReservationStatus::where('status', 'ACCEPTED')->get()->pop()->id; 
    }
    
    /**
     * Retrieve rejected reservation status id
     * @param none
     * @return int
     */
    public static function rejected(): int 
    {
        return ReservationStatus::where('status', 'REJECTED')
            ->get()->pop()->id;
    } 

    /**
     * Retrieve pending reservation status id
     * @param none
     * @return int
     */
    public static function pending() : int
    {
        return ReservationStatus::where('status', 'PENDING')
            ->get()->pop()->id; 
    }

    /**
     * Retrieve cancelled reservation status id
     * @param none
     * @return int
     */
    public static function cancelled(): int 
    {
        return ReservationStatus::where('status', 'CANCELLED')
            ->get()->pop()->id;
    }
}