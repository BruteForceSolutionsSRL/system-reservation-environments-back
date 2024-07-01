<?php
namespace App\Service;

use App\Models\Reservation;
interface ReservationService
{
    function getReservation(int $reservationId): array;
    function getAllReservations(): array;
    function getAllReservationsExceptPending(): array;
    function getPendingRequest(): array;
    function listRequestsByTeacher(int $teacherId): array;
    function listAllRequestsByTeacher(int $teacherId): array;
    function getAllReservationsExceptPendingByTeacher(int $teacherId): array;
    function reject(int $reservationId, string $message, int $personId): string;
    function cancel(int $reservationId, string $message): string;
    function specialCancel(int $specialReservationId): string;
    function accept(int $reservationId, bool $ignoreFlag): string;
    function store(array $data): string;
    function getConflict(int $reservationId): array;
    function cancelAndRejectReservationsByClassroom(int $classroomId): array;
    function getAllReservationsByClassroom(int $classromId): array;
    function getReports(array $data): array;
    function saveSpecialReservation(array $data): string;
}
