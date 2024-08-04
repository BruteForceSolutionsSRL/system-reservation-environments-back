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

    public function getUniversitySubject(int $universitySubjectId): array 
    {
        return $this->formatOutput($this->model::find($universitySubjectId));
    }

    public function delete(int $universitySubjectId): void 
    {
        $universitySubject = UniversitySubject::find($universitySubjectId); 
        $universitySubject->delete();
    }

    public function store(array $data): array 
    {
        $universitySubject = new $this->model();

        $universitySubject->id = $data['cod_sis']; 
        $universitySubject->name = $data['name']; 
        $universitySubject->department_id = $data['department_id']; 
        $universitySubject->save();

        $aux = []; 
        for ($i = 0; $i < count($data['levels']); $i++) {
            array_push(
                $aux, 
                [
                    'study_plan_id' => $data['study_plan_ids'][$i], 
                    'grade' => $data['levels'][$i]
                ]
            );
        }
        $universitySubject->studyPlans()->attach($aux);

        return $this->formatOutput($universitySubject);
    }

    private function formatOutput($universitySubject)
    {
        return [
            'university_subject_id' => $universitySubject->id,
            'name' => $universitySubject->name,
            'groups' => $universitySubject->teacherSubjects->map(
                function ($teacherSubject) {
                    return [
                        'group_number' => $teacherSubject->group_number, 
                        'group_id' => $teacherSubject->id,
                        'person' => [
                            'person_id' => $teacherSubject->person->id, 
                            'name' => $teacherSubject->person->name,
                            'last_name' => $teacherSubject->person->last_name, 
                            'fullname' => $teacherSubject->person->name.' '.$teacherSubject->person->last_name,
                        ],
                    ];
                }  
            )->toArray(),
            'study_plans' => $universitySubject->studyPlans->map(
                function ($studyPlan) {
                    return [
                        'study_plan_id' => $studyPlan->id, 
                        'name' => $studyPlan->name,
                    ];
                }
            )->toArray(),

        ];
    }
}

