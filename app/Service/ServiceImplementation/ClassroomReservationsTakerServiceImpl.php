<?php
namespace App\Service\ServiceImplementation;

class ClassroomReservationsTakerServiceImpl
{
    private $reservationService;
    private $classroomService; 

    private function initReservation()
    {
        $this->reservationService = new ReservationServiceImpl();
    }
    private function initClassroom() 
    {
        $this->classroomService = new ClassroomServiceImpl();
    }

    // methods to assign to reservation service
    public function getActiveReservationsByClassroom(int $classroomId): array
    {
        $this->initReservation(); 
        return $this->reservationService->getActiveReservationsByClassroom($classroomId);
    }

    public function reject(int $reservationId, string $message, int $sender): void
    {
        $this->initReservation();
        $this->reservationService->reject($reservationId, $message, $sender);
    }

    public function cancelAndRejectReservationsByClassroom(int $classroomId): void 
    {
        $this->initReservation();
        $this->reservationService->cancelAndRejectReservationsByClassroom($classroomId);
    }

    // methods to assign to classroom Service
    public function sameBlock(array $classroomIds): bool
    {
        $this->initClassroom();
        return $this->classroomService->sameBlock($classroomIds);
    }
    public function suggestClassrooms(array $data): array
    {
        $this->initClassroom();
        return $this->classroomService->suggestClassrooms($data);
    }
    public function getClassroomsByBlock(int $blockId): array 
    {
        $this->initClassroom();
        return $this->classroomService->getClassroomsByBlock($blockId);
    } 
}