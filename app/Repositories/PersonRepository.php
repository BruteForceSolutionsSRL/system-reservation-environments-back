<?php
namespace App\Repositories; 

use App\Models\Person;
use Illuminate\Cache\Repository; 
class PersonRepository extends Repository
{
    protected $model; 

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve a Person by its ID
     * @param int $personId
     * @return Person
     */
    public function getPerson(int $personId): Person 
    {
        return $this->model::find($personId); 
    }
}