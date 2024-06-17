<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\UniversitySubjectRepository;
use App\Service\UniversitySubjectService;

class UniversitySubjectImpl implements UniversitySubjectService
{
    private $universitySubjectRepository;

    public function __construct()
    {
        $this->universitySubjectRepository = new UniversitySubjectRepository();
    }


    /**
     * get all the subjects registered in the system
     *
     * @return array
     */
    public function getAllUniversitySubject(): array
    {
        return $this->universitySubjectRepository->getAllUniversitySubjects();
    }
}

