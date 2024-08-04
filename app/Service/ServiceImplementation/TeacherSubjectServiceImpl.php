<?php
namespace App\Service\ServiceImplementation;

use App\Http\Controllers\AcademicPeriodController;
use App\Service\TeacherSubjectService;

use App\Models\{
    Reservation,
    TeacherSubject
};

use App\Repositories\{
    AcademicPeriodRepository,
    TeacherSubjectRepository,
    ReservationStatusRepository as ReservationStatuses,
    ReservationRepository
};

class TeacherSubjectServiceImpl implements TeacherSubjectService
{
    private $teacherSubjectRepository; 
    private $academicPeriodRepository;
    private $reservationRepository;

    private $reservationService;

    public function __construct()
    {
        $this->teacherSubjectRepository = new TeacherSubjectRepository();
        $this->academicPeriodRepository = new AcademicPeriodRepository();
        $this->reservationRepository = new ReservationRepository();

        $this->reservationService = new ReservationServiceImpl();
    }

    public function getAllTeacherSubjects() {
        return $this->teacherSubjectRepository->getAllTeacherSubjects(); 
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

    /**
     * 
     * @param array $data
     * @return array
     */
    public function saveGroup(array $data): array
    {
        $data['teacher_subject_id'] = $this->teacherSubjectRepository->saveGroup($data);
        
        foreach ($data['class_schedules'] as $timeSlots ) {
            $reservationData['time_slot_ids'][0] = $timeSlots[0];
            $reservationData['time_slot_ids'][1] = $timeSlots[1];
            $reservationData['quantity'] = 25;
            $reservationData['repeat'] = 7;
            $academicPeriod = $this->academicPeriodRepository->getAcademicPeriodById($data['academic_period_id']);
            $reservationData['date'] = $academicPeriod['initial_date'];
            $reservationData['reservation_reason_id'] = 2;
            $reservationData['reservation_status_id'] = ReservationStatuses::pending();
            $reservationData['priority'] = 0;
            $reservationData['academic_period']['academic_period_id'] = $data['academic_period_id'];
            $reservationData['configuration_flag'] = 1;
            $reservationData['persons'] = [['person_id' => $data['person_id'], 'teacher_subject_ids' => [$data['teacher_subject_id']]]];
            $reservationData['person_id'] = $data['person_id'];
            $reservationData['verified'] = 1;
            $reservationData['classroom_ids'] = $data['classroom_ids'];
            $reservation = $this->reservationRepository->save($reservationData);
            $this->reservationService->accept($reservation['reservation_id'], true);
        }
        return ['message' => 'Grupo creado con exito'];
    }
}