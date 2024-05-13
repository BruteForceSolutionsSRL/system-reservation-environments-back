<?php
namespace App\Repositories; 

use Illuminate\Cache\Repository; 
class PersonRepository extends Repository
{
    protected $model; 

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getPerson($personId) 
    {
        return $this->model::find($personId); 
    }
}