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
                    $item['id'] = $teacherSubject->id; 
                    $item['group_number'] = $teacherSubject->group_number; 
                    
                    return $item;
                }
            )->toArray();
    }

    /**
     * Transform TeacherSubject to array
     * @param TeacherSubject $universitySubject
     * @return array
     */
    private function formatOutputSubject(TeacherSubject $universitySubject): array
    {
        return [
            'subject_id' => $universitySubject->university_subject_id,
            'subject_name' => $universitySubject->universitySubject->name,
        ];
    }

    /**
     * Transform Person to array
     * @param Person $teacher
     * @return array
     */
    private function formatOutputTeacher($teacher): array
    {
        return [
            'person_id' => $teacher['person_id'],
            'teacher_name' => $teacher['person_name'],
            'teacher_last_name' => $teacher['person_lastname'],
        ];
    }
}