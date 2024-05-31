<?php
namespace App\Repositories; 

use App\Models\Person;

use Illuminate\Cache\Repository; 
use Illuminate\Database\Eloquent\Model; 

class PersonRepository extends Repository
{
    protected $model; 

    public function __construct()
    {
        $this->model = Person::class;
    }

    /**
     * Retrieve a Person by its ID
     * @param int $personId
     * @return Person
     */
    public function getPerson(int $personId): array 
    {
        return $this->formatOutput($this->model::find($personId)); 
    }

    private function formatOutput(Person $person): array
    {
        if ($person === null) return [];
        return [
            'person_id' => $person->id, 
            'person_name' => $person->name, 
            'person_lastname' => $person->last_name, 
            'person_email' => $person->email, 
            'person_fullname' => $person->name.' '.$person->last_name
        ]; 
    }
}