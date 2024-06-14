<?php
namespace App\Repositories;

use App\Models\UniversitySubject;
use Illuminate\Database\Eloquent\Model;

class UniversitySubjectRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = UniversitySubject::class;
    }

    /**
     * get all the subjects registered in the system
     *
     * @return array
     */
    public function getAllUniversitySubjects(): array
    {
        return UniversitySubject::all()->map(
            function ($universitySubject)
            {
                return $this->formatOutput($universitySubject);
            }
        )->toArray();
    }

    private function formatOutput($universitySubject)
    {
        return [
            'university_subject_id' => $universitySubject->id,
            'university_subject_name' => $universitySubject->name,
            'university_subject_grade' => $universitySubject->grade,
        ];
    }
}

