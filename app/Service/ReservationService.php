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
    function reject(int $reservationId, string $message): string;
    function cancel(int $reservationId): string;
    function accept(int $reservationId): string;
    function store(array $data): string;
    function getConflict(int $reservationId): array;
    function cancelAndRejectReservationsByClassroom(int $classroomId): array;
    function getAllReservationsByClassroom(int $classromId): array;
}
