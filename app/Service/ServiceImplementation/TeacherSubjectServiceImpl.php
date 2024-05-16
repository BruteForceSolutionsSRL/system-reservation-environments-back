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

    /**
     * Retrieve a list of university subjects with teacher id
     * @param int $teacherID
     * @return array
     */
    public function getSubjectsByTeacherId(int $teacherID): array
    {
        return $this->teacherSubjectRepository
            ->getSubjectsByTeacherID($teacherID);
    }
    
    /**
     * Retrieve a list of teachers which dictates a university subject
     * @param int $universitySubjectID
     * @return array
     */
    public function getTeachersBySubjectId(int $universitySubjectID): array
    {
        return ($this->teacherSubjectRepository
            ->getTeachersBySubject($universitySubjectID)); 
    }
}