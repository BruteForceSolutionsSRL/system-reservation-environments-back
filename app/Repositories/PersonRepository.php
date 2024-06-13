<?php
namespace App\Repositories; 

use App\Models\Person;

use Illuminate\Cache\Repository; 

use Illuminate\Support\Facades\DB;

class PersonRepository extends Repository
{
    protected $model; 

    public static function system()
    {
        return Person::where('name', 'SISTEMA')
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
        return $this->model::where('id', '!=', $this->system())
            ->get()->map(
                function ($person) 
                {
                    return $this->formatOutput($person);
                }
            )->toArray();
    }

    /**
     * Retrieve a list of users based on roles specified
     * @param array $roles
     * @return array
     */
    public function getUsersByRole(array $roles): array 
    {
        return $this->model::whereHas('roles', 
            function ($query) use ($roles) 
            {
                foreach ($roles as $rol)
                    $query->orWhere('roles.id', $rol);
            }
        )->get()->map(
            function ($user) 
            {
                return $this->formatOutput($user);
            }
        )->toArray();
    }

    /**
     * Transform Person to array
     * @param Person $person
     * @return array
     */
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

    /**
	 * Retrieve all permissions of a person
	 * @param array $data
	 * @return array
	 */
	public function havePermission(array $data):array 
	{
        $permissions = DB::table('permissions')
            ->join('role_permission', 'permissions.id', '=', 'role_permission.permission_id')
            ->join('roles', 'role_permission.role_id', '=', 'roles.id')
            ->join('person_role', 'roles.id', '=', 'person_role.role_id')
            ->join('people', 'person_role.person_id', '=', 'people.id')
            ->where('people.id', $data['person_id'])
            ->select('permissions.name')
            ->distinct()
            ->get()
            ->toArray();
        return $permissions;
	}
}