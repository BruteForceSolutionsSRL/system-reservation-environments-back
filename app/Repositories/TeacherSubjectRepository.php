<?php
namespace App\Repositories;

use App\Models\{
    TeacherSubject,
    Person
};

class TeacherSubjectRepository
{
    protected $model;
    private $personRepository; 
    public function __construct()
    {
        $this->model = TeacherSubject::class; 
        
        $this->personRepository = new PersonRepository();
    }

    public function getAllTeacherSubjects() {
        return $this->model::all()->map(
            function ($teacherSubject) {
                return $this->formatOutputSubject($teacherSubject);
            }
        )->toArray();
    }

    /**
     * Retrieve a list of university subjects by teacher id
     * @param int $teacherID
     * @return array
     */
    public function getSubjectsByTeacherID(int $teacherID): array 
    {
        return $this->model::with('universitySubject:id,name')
                    ->select('university_subject_id')
                    ->where('person_id', $teacherID)
                    ->groupBy('university_subject_id')
                    ->get()->map(
                        function ($universitySubject) 
                        {
                            return $this->formatOutputSubject($universitySubject);
                        }
                    )->toArray();
    }

    /**
     * Retrieve a list of all teachers by university subject id
     * @param int $universitySubjectID
     * @return array
     */
    public function getTeachersBySubject(int $universitySubjectID): array
    {
        return $this->model::with('person')
            ->where('university_subject_id', $universitySubjectID)
            ->select('id','person_id', 'group_number')
            ->get()->map(
                function ($teacherSubject)
                {
                    $teacher = $this->personRepository->getPerson(
                        $teacherSubject->person_id
                    );
                    
                    $item = $this->formatOutputTeacher($teacher); 
                    $item['teacher_subject_id'] = $teacherSubject->id; 
                    $item['group_number'] = $teacherSubject->group_number; 
                    
                    return $item;
                }
            )->toArray();
    }

    /**
     * Check if an array of teacher subjects are from the same university subject
     */
    public function sameSubject(array $teacherSubjectIds): bool 
    {
        if (empty($teacherSubjectIds)) {
            return true;
        }
        $subjectId = $this->model::find($teacherSubjectIds[0])->first()->university_subject_id;
        foreach ($teacherSubjectIds as $teacherSubjectId) {
            $auxSubjectId = $this->model::find($teacherSubjectId)->first()->university_subject_id; 
            if ($auxSubjectId !== $subjectId) return false;
        }
        return true;
    }

    /**
     * Transform TeacherSubject to array
     * @param TeacherSubject $universitySubject
     * @return array
     */
    private function formatOutputSubject(TeacherSubject $universitySubject): array
    {
        return [
            'group_id' => $universitySubject->id, 
            'group_number' => $universitySubject->group_number,
            'subject_id' => $universitySubject->university_subject_id,
            'subject_name' => $universitySubject->universitySubject->name,
            'person' => [
                'person_id' => $universitySubject->person->id, 
                'name' => $universitySubject->person->name, 
                'last_name' => $universitySubject->person->last_name,
                'fullname' => $universitySubject->person->name.' '.$universitySubject->person->last_name,
            ],
        ];
    }

    /**
     * 
     * @param array $data
     * @return array
     */
    public function saveGroup(array $data):array
    {
        $teacherSubject = new TeacherSubject();
        $teacherSubject->group_number = $data['group_number'];
        $teacherSubject->person_id = $data['person_id'];  
        $teacherSubject->university_subject_id = $data['university_subject_id'];
        $teacherSubject->save();

        return $this->formatOutputSubject($teacherSubject);
    }

    /**
     * Transform Person to array
     * @param array $teacher
     * @return array
     */
    private function formatOutputTeacher($teacher): array
    {
        return [
            'person_id' => $teacher['person_id'],
            'teacher_name' => $teacher['name'],
            'teacher_last_name' => $teacher['lastname'],
        ];
    }
}