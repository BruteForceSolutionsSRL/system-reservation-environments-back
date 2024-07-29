<?php
namespace App\Repositories;

use App\Models\Faculty; 

class FacultyRepository
{
    protected $model; 
    
    public function __construct()
    {
        $this->model = Faculty::class; 
    }

    public function getAllFaculties(): array
    {
        return $this->model::all()->map(
            function ($faculty) 
            {
                return $this->formatOutput($faculty);
            }
        )->toArray();
    }

    public function getAllFacultiesByUser(int $personId, string $date): array 
    {
        return $this->model::whereHas('academicPeriods', 
                function ($query) use ($date) 
                {
                    $query->where('initial_date', '<=', $date)
                        ->where('end_date', '>=', $date); 
                }
            )->whereHas('academicPeriods.studyPlans.universitySubjects.teacherSubjects', 
                function ($query) use ($personId)
                {
                    $query->where('person_id', $personId); 
                }
            )->get()->map(
                function ($faculty)
                {
                    return $this->formatOutput($faculty); 
                }
            )->toArray();
    }

    public function getFacultyByID(int $facultyId): array 
    {
        return $this->formatOutput($this->model::find($facultyId));
    }

    public function formatOutput($faculty): array 
    {
        return [
            'faculty_id' => $faculty->id,
            'name' => $faculty->name,
            //'time_slot' => $faculty->timeSlot->id,
        ];
    }
}