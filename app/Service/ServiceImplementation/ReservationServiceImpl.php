<?php
namespace App\Service\ServiceImplementation;

use App\Service\ReservationService;

use Illuminate\Database\Eloquent\Collection;

use App\Models\{
    Reservation,
    Person,
    Classroom,
};
use App\Repositories\{
    PersonRepository,
    ReservationStatusRepository as ReservationStatuses,
    ReservationRepository, 
    NotificationTypeRepository
};

class ReservationServiceImpl implements ReservationService
{
    private $personRepository;
    private $reservationRepository;

    private $mailService;
    private $timeSlotService;
    private $notificationService;

    public function __construct()
    {
        $this->personRepository = new PersonRepository();
        $this->reservationRepository = new ReservationRepository();

        $this->timeSlotService = new TimeSlotServiceImpl();
        $this->mailService = new MailerServiceImpl();
        $this->notificationService = new NotificationServiceImpl();
    }

    /**
     * Retrieve a list of all reservations
     * @param none
     * @return array
     */
    public function getAllReservations(): array
    {
        return $this->reservationRepository->getAllReservations();
    }

    /**
     * Retrieve a list of all reservations except pending requests
     * @return array
     */
    public function getAllReservationsExceptPending(): array
    {
        return $this->reservationRepository->getReservationsWithoutPendingRequest();
    }

    /**
     * Retrieve a single reservation based on its ID
     * @param int $reservationId
     * @return array
     */
    public function getReservation(int $reservationId): array
    {
        return $this->reservationRepository->getReservation($reservationId);
    }

    /**
     * Retrieve a list of all pending request
     * @param none
     * @return array
     */
    public function getPendingRequest(): array
    {
        return $this->reservationRepository->getPendingRequest();
    }

    /**
     * Retrieve a list of accepted/pending by a teacherId
     * @param int $teacherId
     * @return array
     */
    public function listRequestsByTeacher(int $teacherId): array
    {
        $teacher = $this->personRepository->getPerson($teacherId);
        if ($teacher === []) {
            return ['message' => 'No existe el docente'];
        }
        return $this->reservationRepository->getRequestByTeacher($teacherId);
    }

    /**
     * Retrieve a list of all request by teacherId
     * @param int $teacherId
     * @return array
     */
    public function listAllRequestsByTeacher(int $teacherId): array
    {
        $teacher = $this->personRepository->getPerson($teacherId);
        if ($teacher === []) {
            return ['message' => 'No existe el docente'];
        }
        return $this->reservationRepository->getAllRequestByTeacher($teacherId);
    }

    /**
     * Retrieve a list of all reservations except pending requests
     * by teacherId
     * @param int $teacherId
     * @return array
     */
    public function getAllReservationsExceptPendingByTeacher(int $teacherId): array
    {
        return $this->reservationRepository
                ->getReservationsWithoutPendingRequestByTeacher($teacherId);
    }

    /**
     * Function to reject a reservation based on its ID
     * @param int $reservationId
     * @return string
     */
    public function reject(int $reservationId, string $message): string
    {
        $reservation = Reservation::find($reservationId);

        if ($reservation == null) {
            return 'No existe una solicitud con este ID';
        }

        $reservationStatusId = $reservation->reservation_status_id;
        if ($reservationStatusId == ReservationStatuses::rejected()) {
            return 'Esta solicitud ya fue rechazada';
        }

        if ($reservationStatusId == ReservationStatuses::pending()) {
            $reservation->reservation_status_id = ReservationStatuses::rejected();
            $reservation->save();

            $emailData = $this->notificationService->store(
                [
                    'title' => 'SOLICITUD DE RESERVA #'.$reservation->id.' RECHAZADA', 
                    'body' => 'Se rechazo la solicitud #'.$reservation->id.' '.$message,
                    'type' => NotificationTypeRepository::accepted(),
                    'sendBy' => $this->personRepository->system(), 
                    'to' => $reservation->teacherSubjects->map(
                        function ($teacher) 
                        {
                            return $teacher->person_id;
                        }
                    )
                ]
            );
            $emailData = array_merge(
                $emailData, 
                $this->reservationRepository->formatOutput($reservation)
            );

            $this->mailService->rejectReservation($emailData);

            return 'La solicitud de reserva fue rechazada.';
        } else {
            return 'Esta solicitud ya fue atendida';
        }
    }

    /**
     * Function to cancel a accepted/pending reservation based on its ID
     * @param int $reservationId
     * @return string
     */
    public function cancel(int $reservationId): string
    {
        $reservation = Reservation::find($reservationId);

        if ($reservation == null) {
            return 'No existe una solicitud con este ID';
        }

        $reservationStatusId = $reservation->reservation_status_id;
        if ($reservationStatusId == ReservationStatuses::cancelled()) {
            return 'Esta solicitud ya fue cancelada';
        }
        if ($reservationStatusId == ReservationStatuses::rejected()) {
            return 'Esta solicitud ya fue rechazada';
        }

        $reservation->reservation_status_id = ReservationStatuses::cancelled();
        $reservation->save();

        $emailData = $this->notificationService->store(
            [
                'title' => 'SOLICITUD DE RESERVA #'.$reservation->id.' CANCELADA', 
                'body' => 'Se cancela la solicitud #'.$reservation->id,
                'type' => NotificationTypeRepository::cancelled(),
                'sendBy' => $this->personRepository->system(), 
                'to' => $reservation->teacherSubjects->map(
                    function ($teacher) 
                    {
                        return $teacher->person_id;
                    }
                )
            ]
        );    
        $emailData = array_merge($emailData, $this->reservationRepository->formatOutput($reservation));

        $this->mailService->cancelReservation($emailData);

        return 'La solicitud de reserva fue cancelada.';
    }

    /**
     * Function to accept a request of reservation based on its ID
     * @param int $reservationId
     * @return string
     */
    public function accept(int $reservationId): string
    {
        $reservation = Reservation::find($reservationId);
        if ($reservation == null) {
            return 'La solicitud de reserva no existe';
        }

        $reservationStatus = $reservation->reservationStatus->id;
        if ($reservationStatus != ReservationStatuses::pending()) {
            return 'Esta solicitud ya fue atendida';
        }
        if (!$this->checkAvailibility($reservation)) {
<<<<<<< Updated upstream
            $this->reject(
                $reservation->id,
                'Se rechazo su solicitud, contacte con un administrador'
            );
=======
            $this->reject($reservation->id,'Su solicitud ha sido rechazada');
>>>>>>> Stashed changes
            return  'La solicitud no puede aceptarse, existen ambientes ocupados';
        }

        $reservation->reservation_status_id = ReservationStatuses::accepted();
        $reservation->save();

        $message = '';

        $times = $this->timeSlotService->getTimeSlotsSorted($reservation->timeSlots);
        foreach ($reservation->classrooms as $classroom) {
            $reservationSet = $this->reservationRepository
                ->getActiveReservationsWithDateStatusClassroomTimes(
                    [ReservationStatuses::pending()],
                    $reservation->date,
                    $classroom->id,
                    $times
                );
            foreach ($reservationSet as $reservationIterable)
<<<<<<< Updated upstream
                $message .= $this->reject(
                    $reservationIterable->id,
                    'Se rechazo su solicitud, contacte con un administrador'
                );
=======
                $message .= $this->reject($reservationIterable->id, 'Su reserva ha sido rechazada');
>>>>>>> Stashed changes
        }

        $emailData = $this->notificationService->store(
            [
                'title' => 'SOLICITUD DE RESERVA #'.$reservation->id.' ACEPTADA', 
                'body' => 'Se acepto la solicitud #'.$reservation->id,
                'type' => NotificationTypeRepository::accepted(),
                'sendBy' => $this->personRepository->system(), 
                'to' => $reservation->teacherSubjects->map(
                    function ($teacher) 
                    {
                        return $teacher->person_id;
                    }
                )
            ]
        );    
        $this->mailService->acceptReservation($emailData);

        return 'La reserva fue aceptada correctamente';
    }

    /**
     * Function to store full data about a request and automatic accept/reject
     * @param array $data
     * @return string
     */
    public function store(array $data): string
    {
        $block_id = -1;
        foreach ($data['classroom_id'] as $classroomId) {
            $classroom = Classroom::find($classroomId);
            if ($block_id == -1) $block_id = $classroom->block_id;
            if ($classroom->block_id != $block_id) {
                return  'Los ambientes no pertenecen al bloque';
            }
        }

        if (!array_key_exists('repeat', $data)) {
            $data['repeat'] = 0;
        }

        $reservation = $this->reservationRepository->save($data);

        $emailData = $this->notificationService->store(
            [
                'title' => 'SOLICITUD DE RESERVA #'.$reservation->id.' PENDIENTE', 
                'body' => 'Se envio la solicitud #'.$reservation->id,
                'type' => NotificationTypeRepository::accepted(),
                'sendBy' => $this->personRepository->system(), 
                'to' => $reservation->teacherSubjects->map(
                    function ($teacher) 
                    {
                        return $teacher->person_id;
                    }
                )
            ]
        );

        $emailData = array_merge(
            $emailData, 
            $this->reservationRepository->formatOutput($reservation)
        );

        $this->mailService->createReservation($emailData);

        if ($this->checkAvailibility($reservation)) {
            if ($this->alertReservation($reservation)['ok'] != 0) {
                return  'Tu solicitud debe ser revisada por un administrador, se enviara una notificacion para mas detalles';
            }
            $reservation->reservation_status_id = ReservationStatuses::accepted();
            $reservation->save();

            $emailData = $this->notificationService->store(
                [
                    'title' => 'SOLICITUD DE RESERVA #'.$reservation->id.' ACEPTADA', 
                    'body' => 'Se acepto la solicitud #'.$reservation->id,
                    'type' => NotificationTypeRepository::accepted(),
                    'sendBy' => $this->personRepository->system(), 
                    'to' => $reservation->teacherSubjects->map(
                        function ($teacher) 
                        {
                            return $teacher->person_id;
                        }
                    )
                ]
            );    

            $emailData = array_merge($emailData, $this->reservationRepository->formatOutput($reservation));

            $this->mailService->acceptReservation($emailData);
            return 'Tu solicitud de reserva fue aceptada';
        } else {
            return $this->reject(
                $reservation->id,
                'Se rechazo su solicitud, contacte con un administrador'
            );
        }
    }

    /**
     * Retrieve an array of all conflicts of a reservation
     * @param int $reservationId
     * @return array
     */
    public function getConflict(int $reservationId): array
    {
        $reservation = Reservation::find($reservationId);
        if ($reservation == null) {
            return ['meesage' => 'La reserva no existe'];
        }
        $result = $this->alertReservation($reservation);
        unset($result['ok']);
        return $result;
    }

    /**
     * Function to check availability for all classrooms to do a reservation in a step
     * @param Reservation $reservation
     * @return boolean
     */
    private function checkAvailibility(Reservation $reservation): bool
    {
        $time = $this->timeSlotService->getTimeSlotsSorted($reservation->timeSlots);
        foreach ($reservation->classrooms as $classroom) {
            $reservations = $this->reservationRepository
                ->getActiveReservationsWithDateStatusClassroomTimes(
                    [ReservationStatuses::accepted()],
                    $reservation->date,
                    $classroom->id,
                    $time
                );
            if (count($reservations) != 0)
                return false;
        }
        return true;
    }

    /**
     * Check if a reservation in pending status have conflicts or is really `weird`
     * @param Reservation $reservation
     * @return array
     */
    public function alertReservation(Reservation $reservation): array
    {
        $result = [
            'quantity' => '',
            'classroom' => [
                'message' => '',
                'list' => array()
            ],
            'ok' => 0
        ];
        $totalCapacity = $this->getTotalCapacity($reservation->classrooms);
        if ($totalCapacity < $reservation->number_of_students) {
            $result['quantity'] .= 'la cantidad de estudiantes excede la capacidad de estudiantes.\n';
            $result['ok'] = 1;
        }

        $usagePercent = $reservation->number_of_students / $totalCapacity * 100;
        if ($usagePercent < 50.0) {
            $message = 'la capacidad de los ambientes solicitados es muy elevada para la capacidad de ambientes solicitados.\n';
            $result['quantity'] .= $message;
            $result['ok'] = 1;
        }

        if ($this->getTotalFloors($reservation->classrooms) > 2) {
            $result['ok'] = 1;
            $message = 'los ambientes solicitados, se encuentran en mas de 2 pisos diferentes\n';
            $result['classroom']['message'] .= $message;
        }

        foreach ($reservation->classrooms as $classroom) {
            $reservations = $this->reservationRepository
                ->getActiveReservationsWithDateStatusAndClassroom(
                    [ReservationStatuses::pending()],
                    $reservation->date,
                    $classroom->id
                );
            if (count($reservations) > 1) {
                $result['ok'] = 1;
                array_push($result['classroom']['list'], $classroom->name);
            }
        }

        $result['classroom']['list'] = array_unique($result['classroom']['list']);
        return $result;
    }

    /**
     * Function to get Total Capacity of a set of classrooms
     * @param Collection $classrooms
     * @return int
     */
    public function getTotalCapacity(Collection $classrooms): int
    {
        $total = 0;
        foreach ($classrooms as $classroom)
            $total += $classroom->capacity;
        return $total;
    }

    /**
     * Indicates if a reservation is inside the date and time slots
     * @param Reservation $reservation
     * @param string $date
     * @param array $times
     * @return bool
     */
    private function isInside(
        Reservation $reservation,
        string $date,
        array $times
    ): bool {
        if ($date == $reservation->date) {
            $time = $this->timeSlotService
                ->getTimeSlotsSorted($reservation->timeSlots);
            if (!($time[1] <= $times[0] || $time[0] >= $times[1])) {
                return true;
            }
        } else {
            $initialDate = new \DateTime($date);
            if ($reservation->repeat > 0) {
                $goalDate = new \DateTime($reservation->date);
                $repeat = $reservation->repeat;

                $difference = $initialDate->diff($goalDate)->days;
                if ($difference % $repeat == 0) {
                    $time = $this->timeSlotService
                        ->getTimeSlotsSorted($reservation->timeSlots);
                    if (!(($time[1] <= $times[0]) || ($time[0] >= $times[1]))) {
                        return true;
                    }
                }
            }

        }
        return false;
    }

    /**
     * Retrieve a list of accepted/pending reservations
     * @param int $classroomId
     * @return array
     */
    public function getActiveReservationsByClassroom(int $classroomId): array
    {
        return $this->reservationRepository->getReservationsByClassroomAndStatuses(
            $classroomId,
            [
                ReservationStatuses::accepted(),
                ReservationStatuses::pending()
            ]
        );
    }

    /**
     * Retrieve a number of floors used in a set of classrooms
     * @param array $classrooms
     * @return int
     */
    private function getTotalFloors($classrooms): int
    {
        $dp = [];
        $usedFloors = 0;
        foreach ($classrooms as $classroom) {
            $floor = $classroom->floor;
            if (!array_key_exists($floor, $dp)) $dp[$floor] = 0;
            if ($dp[$floor] == 0) {
                $dp[$floor] = 1;
                $usedFloors++;
            }
        }
        return $usedFloors;
    }

    /**
     * Function to cancel and reject all reservations related to a classroom in the process to delete
     * @param int $classroomId
     * @return array
     */
    public function cancelAndRejectReservationsByClassroom(int $classroomId): array
    {
        $reservations = $this->reservationRepository
            ->getAcceptedAndPendingReservationsByClassroom($classroomId);

        $acceptedReservations = $reservations['accepted'];
        $pendingReservations = $reservations['pending'];

        if (!empty($acceptedReservations) || !empty($pendingReservations)) {
            foreach ($acceptedReservations as $reservationId) {
                $this->cancel($reservationId);
            }

            foreach ($pendingReservations as $reservationId) {
<<<<<<< Updated upstream
                $this->reject(
                    $reservationId,
                    'Se rechazo su solicitud, contacte con un administrador'
                );
=======
                $this->reject($reservationId,'Su solicitud ha sido rechazada');
>>>>>>> Stashed changes
            }

            return ['Todas las solicitudes asociadas al ambiente fueron canceladas/rechazadas.'];
        } else {
            return ['El ambiente no está asociado a ninguna reserva.'];
        }
    }
    
    /**
     * Function to get reservations accepted, pending and reject
     * @param int $classromId
     * @return array
     */
    public function getAllReservationsByClassroom(int $classromId): array
    {
        $reservations = $this->reservationRepository
            ->getReservationsByClassroomAndStatuses(
                $classromId,
                [
                    ReservationStatuses::accepted(),
                    ReservationStatuses::pending(),
                    ReservationStatuses::rejected()
                ]
            );
        return $this->reservationRepository->formatOutputGARBC($reservations);
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function getReports(array $data): array
    {
        return $this->reservationRepository->getReports($data);
    }
}
