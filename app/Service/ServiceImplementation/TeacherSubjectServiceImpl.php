<?php
namespace App\Service\ServiceImplementation;

use App\Service\TeacherSubjectService;

use App\Repositories\TeacherSubjectRepository; 

class TeacherSubjectServiceImpl implements TeacherSubjectService
{
    private $teacherSubjectRepository; 
    public function __construct()
    {
        $this->teacherSubjectRepository = new TeacherSubjectRepository();
    }
    public function getSubjectsByTeacherId(int $teacherID): array
    {
        return $this->teacherSubjectRepository
            ->getSubjectsByTeacherID($teacherID);
    }
    public function getTeachersBySubjectId(int $universitySubjectID): array
    {
        return ($this->teacherSubjectRepository
            ->getTeachersBySubject($universitySubjectID)); 
    }
}