<?php
namespace App\Service\ServiceImplementation;

use App\Service\ReservationService;

use Illuminate\Database\Eloquent\Collection;

use App\Models\{
    Reservation,
    Classroom,
};
use App\Repositories\{
    PersonRepository,
    ReservationStatusRepository as ReservationStatuses,
    ReservationRepository
};
use Carbon\Carbon;

class ReservationServiceImpl implements ReservationService
{
    private $personRepository;
    private $reservationRepository;

    private $mailService;
    private $timeSlotService;
    private $notificationService;
    private $classroomService; 

    public function __construct()
    {
        $this->personRepository = new PersonRepository();
        $this->reservationRepository = new ReservationRepository();

        $this->timeSlotService = new TimeSlotServiceImpl();
        $this->mailService = new MailerServiceImpl();
        $this->notificationService = new NotificationServiceImpl();
        $this->classroomService = new ClassroomReservationsTakerServiceImpl();
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
        return $this->reservationRepository->getAllActiveRequestByUser($teacherId);
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
    public function reject(int $reservationId, string $message, int $personId): string
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

            $this->notificationService->store(
                $this->mailService->rejectReservation(
                    $this->reservationRepository->formatOutput($reservation),
                    PersonRepository::system(), 
                    $message
                )
            );

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

        $this->notificationService->store(
            $this->mailService->cancelReservation(
                $this->reservationRepository->formatOutput($reservation),
                PersonRepository::system()
            )
        );
        return 'La solicitud de reserva fue cancelada.';
    }

    /**
     * Function to accept a request of reservation based on its ID
     * @param int $reservationId
     * @return string
     */
    public function accept(int $reservationId, bool $ignoreFlag): string
    {
        $reservation = $this->reservationRepository->getReservation($reservationId);
        if ($reservation == []) {
            return 'La solicitud de reserva no existe';
        }

        if ($reservation['reservation_status'] != 'PENDIENTE') {
            return 'Esta solicitud ya fue atendida';
        }

        if ($this->isExpired($reservation)) {
            return 'Esta solicitud ya es expirada, no puede atenderse';
        }

        if (!$this->checkAvailibility($reservation)) {
            $this->reject(
                $reservation['reservation_id'],
                'Se rechazo su solicitud, existe una reserva de ambientes que ya fue atendida',
                PersonRepository::system()
            );
            return 'La solicitud se rechazo, existen ambientes ocupados';
        }

        if (!$ignoreFlag && ($this->alertReservation($reservation)['ok']!=0)) {
            return 'Tu solicitud debe ser revisada por un encargado responsable, producido por que existen advertencias en tu reserva.';
        }

        $reservation = $this->reservationRepository->updateReservationStatus(
            $reservation['reservation_id'],
            ReservationStatuses::accepted()
        );

        $reservationSet = $this->reservationRepository->getReservations(
            [
                'dates' => [
                    'date_start' => $reservation['date'], 
                    'date_end' => $reservation['date']
                ],
                'reservation_statuses' => [
                    ReservationStatuses::pending()
                ],
                'time_slots' => $this->timeSlotService->getTimeSlotsSorted(
                    $reservation['time_slot']
                ),
                'classrooms' => array_map(
                    function ($classroom) 
                    {
                        return $classroom['classroom_id'];
                    },
                    $reservation['classrooms']
                )
            ]
        );

        foreach ($reservationSet as $reservationIterable)
            $this->reject(
                $reservationIterable['reservation_id'],
                'Se rechazo su solicitud, dado que las aulas solicitadas fueron asignadas a otra solicitud',
                PersonRepository::system()
            );

            $this->notificationService->store(
            $this->mailService->acceptReservation(
                $reservation,
                PersonRepository::system()
            )
        );

        return 'La reserva fue aceptada correctamente';
    }

    /**
     * Function to store full data about a request and automatic accept/reject
     * @param array $data
     * @return mixed
     */
    public function store(array $data): string
    {
        if (!array_key_exists('classroom_id', $data) || count($data['classroom_id']) == 0) {
            $data['classroom_id'] = $this->classroomService->suggestClassrooms(
                [
                    'date' => $data['date'], 
                    'time_slot_id' => $data['time_slot_id'],
                    'block_id' => $data['block_id'],
                    'quantity' => $data['quantity']
                ]
            );
            if (empty($data['classroom_id']) || ($data['classroom_id'] == ['No existe una sugerencia apropiada'])) 
                return 'No existen ambientes disponibles que cumplan con los requerimientos de la solicitud';
            $data['classroom_id'] = array_map(
                function ($classroom) {
                    return $classroom['classroom_id'];
                }, $data['classroom_id']
            );
        }

        if (!$this->classroomService->sameBlock($data['classroom_id'])) {
            return 'Los ambientes no pertenecen al bloque';
        }

        if (!array_key_exists('repeat', $data)) {
            $data['repeat'] = 0;
        }

        $reservation = $this->reservationRepository->save($data);

        $this->notificationService->store(
            $this->mailService->createReservation(
                $reservation,
                PersonRepository::system()
            )
        );

        return $this->accept($reservation['reservation_id'], false); 
    }

    /**
     * Retrieve an array of all conflicts of a reservation
     * @param int $reservationId
     * @return array
     */
    public function getConflict(int $reservationId): array
    {
        $reservation = $this->reservationRepository->getReservation($reservationId);
        if ($reservation == []) {
            return ['meesage' => 'La reserva no existe'];
        }
        $result = $this->alertReservation($reservation);
        unset($result['ok']);
        return $result;
    }

    /**
     * Function to check availability for all classrooms to do a reservation in a step
     * @param array $reservation
     * @return boolean
     */
    private function checkAvailibility(array $reservation): bool
    {
        return count($this->reservationRepository->getReservations(
            [
                'dates' => [
                    'date_start' => $reservation['date'], 
                    'date_end' => $reservation['date']
                ],
                'reservation_statuses' => [
                    ReservationStatuses::accepted()
                ],
                'time_slots' => $this->timeSlotService->getTimeSlotsSorted(
                    $reservation['time_slot']
                ),
                'classrooms' => array_map(
                    function ($classroom) 
                    {
                        return $classroom['classroom_id'];
                    },
                    $reservation['classrooms']
                )
            ]
        )) == 0;
    }

    /**
     * Check if a reservation in pending status have conflicts or is really `weird`
     * @param Reservation $reservation
     * @return array
     */
    public function alertReservation(array $reservation): array
    {
        $result = [
            'quantity' => '',
            'classroom' => [
                'message' => '',
                'list' => array()
            ],
            'ok' => 0
        ];
        $totalCapacity = $this->getTotalCapacity($reservation['classrooms']);

        $usagePercent = $reservation['quantity'] / $totalCapacity * 100;
        if ($usagePercent < 50.0) {
            $message = 'la capacidad de los ambientes solicitados es muy elevada para la cantidad de estudiantes.';
            $result['quantity'] .= $message;
            $result['ok'] = 1;
        }

        if ($usagePercent > 150.0) {
            $result['quantity'].='La capacidad de los ambientes solicitados en muy baja para la cantidad de estudiantes';
            $result['ok'] = 1;
        } 

        if ($this->getTotalFloors($reservation['classrooms']) > 2) {
            $result['ok'] = 1;
            $message = 'los ambientes solicitados, se encuentran en mas de 2 pisos diferentes.';
            $result['classroom']['message'] .= $message;
        }

        $classrooms = [];
        foreach ($reservation['classrooms'] as $classroom) 
            $classroom[$classroom['classroom_name']] = 0;

        $reservationSet = $this->reservationRepository->getReservations(
            [
                'dates' => [
                    'date_start' => $reservation['date'], 
                    'date_end' => $reservation['date']
                ],
                'reservation_statuses' => [
                    ReservationStatuses::pending()
                ],
                'time_slots' => $this->timeSlotService->getTimeSlotsSorted(
                    $reservation['time_slot']
                ),
                'classrooms' => array_map(
                    function ($classroom) 
                    {
                        return $classroom['classroom_id'];
                    },
                    $reservation['classrooms']
                )
            ]
        );

        foreach ($reservationSet as $reservation) {
            foreach ($reservation['classrooms'] as $classroom) {
                if (!array_key_exists($classroom['classroom_name'], $classrooms)) 
                    continue;
                if ($classrooms[$classroom['classroom_name']] == 0) {
                    $classrooms[$classroom['classroom_name']] = 1;
                    array_merge($result['classroom']['list'], $classroom['classroom_name']);
                }
            }
        }

        if (count($result['classroom']['list']) != 0) 
            $result['classroom']['message'] .= 'Existen ambientes que se quieren ocupar.';
        return $result;
    }

    /**
     * Function to get Total Capacity of a set of classrooms
     * @param array $classrooms
     * @return int
     */
    public function getTotalCapacity(array $classrooms): int
    {
        $total = 0;
        foreach ($classrooms as $classroom)
            $total += $classroom['capacity'];
        return $total;
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
    private function getTotalFloors(array $classrooms): int
    {
        $dp = [];
        foreach ($classrooms as $classroom) {
            $floor = $classroom['floor'];
            if (!array_key_exists($floor, $dp)) 
                $dp[$floor] = 0;
            if ($dp[$floor] == 0) 
                $dp[$floor] = 1;
        }
        return count($dp);
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
                $this->reject(
                    $reservationId,
                    'Se rechazo su solicitud, un ambiente en especifico de su solicitud acaba de ser actualizado',
                    PersonRepository::system()
                );
            }

            return ['Todas las solicitudes asociadas al ambiente fueron canceladas/rechazadas.'];
        } else {
            return ['El ambiente no está asociado a ninguna reserva.'];
        }
    }

    /**
     * Cancel accepted reservations and reject pending reservations in an array
     * @param array $reservations
     * @return none
     */
    public function cancelAndRejectReservations(array $reservations): void
    {
        foreach ($reservations as $reservation) {
            if ($reservation['reservation_status'] == 'ACEPTADO') {
                $this->cancel($reservation['reservation_id']);
            } else {
                $this->reject(
                    $reservation['reservation_id'],
                    'Se rechazo la solicitud de reserva, contacte con el encargado para mayor detalle',
                    PersonRepository::system()
                );
            }
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

    /**
     * Function to check if a reservation is expired
     * @param array $reservation
     * @return bool
     */
    public function isExpired(array $reservation): bool 
    {
        $now = Carbon::now();
        $requestedHour = Carbon::parse($reservation['date'].' '.$reservation['time_slot'][0])->addHours(4); 
        return ($now > $requestedHour);
    }
}
