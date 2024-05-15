<?php
namespace App\Repositories;

use App\Models\{
    UniversitySubject, 
    TeacherSubject,
    Person
};

class TeacherSubjectRepository
{
    private $personRepository; 
    public function __construct()
    {
        $this->personRepository = new PersonRepository(Person::class);
    }
    public function getSubjectsByTeacherID(int $teacherID): array 
    {
        return TeacherSubject::with('universitySubject:id,name')
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
    public function getTeachersBySubject(int $universitySubjectID): array
    {
        return TeacherSubject::with('person')
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
    private function formatOutputSubject(TeacherSubject $universitySubject): array
    {
        return [
            'subject_id' => $universitySubject->university_subject_id,
            'subject_name' => $universitySubject->universitySubject->name,
        ];
    }
    private function formatOutputTeacher(Person $teacher): array
    {
        return [
            'person_id' => $teacher->id,
            'teacher_name' => $teacher->name,
            'teacher_last_name' => $teacher->last_name,
        ];
    }
}