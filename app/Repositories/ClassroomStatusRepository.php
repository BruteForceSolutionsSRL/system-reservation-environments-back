<?php
namespace App\Repositories;

use App\Models\ClassroomStatus; 

use Illuminate\Cache\Repository; 
use Illuminate\Database\Eloquent\Model; 

class ClassroomStatusRepository extends Repository
{
     
    private $model; 
    function __construct(Model $model) 
    {
        $this->model = $model;
    }

    public static function available() 
    {   
        return ClassroomStatus::where('name', 'HABILITADO')
            ->get()->pop()->id; 
    }
    public static function disabled() 
    {
        return ClassroomStatus::where('name', 'DESABILITADO')
            ->get()->pop()->id;
    } 
    public static function deleted() {
        return ClassroomStatus::where('name', 'ELIMINADO')
            ->get()->pop()->id; 
    }
}