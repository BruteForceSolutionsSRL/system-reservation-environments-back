<?php
namespace App\Repositories; 

use App\Models\Person;

use Illuminate\Cache\Repository; 
use Illuminate\Database\Eloquent\Model; 

class PersonRepository extends Repository
{
    protected $model; 

    public function system()
    {
        return $this->model::where('name', 'SISTEMA')
            ->get()
            ->pop()
            ->id;
    }

    public function __construct()
    {
        $this->model = Person::class;
    }

    /**
     * Retrieve a Person by its ID
     * @param int $personId
     * @return array
     */
    public function getPerson(int $personId): array 
    {
        $person = $this->model::find($personId); 
        if ($person === null) return [];
        return $this->formatOutput($person); 
    }

    /**
     * Retrieve all users
     * @param none
     * @return array
     */
    public function getAllPersons(): array
    {
        return $this->model::where('name', '!=', 'SISTEMA')
            ->get()->map(
                function ($person) 
                {
                    return $this->formatOutput($person);
                }
            )->toArray();
    }

    public function formatOutput($person): array
    {
        return [
            'person_id' => $person->id, 
            'person_name' => $person->name, 
            'person_lastname' => $person->last_name, 
            'person_email' => $person->email, 
            'person_fullname' => $person->name.' '.$person->last_name
        ]; 
    }
}