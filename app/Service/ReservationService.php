<?php
namespace App\Service; 

interface ReservationService 
{
    function getReservation(int $reservationId): array; 
    function getAllReservations(): array;
    function getPendingRequest(): array; 
    function listRequestsByTeacher(int $teacherId): array; 
    function listAllRequestsByTeacher(int $teacherId): array; 
    function reject(int $reservationId): string; 
    function cancel(int $reservationId): string; 
    function accept(int $reservationId): string; 
    function store(array $data): string; 
    function getConflict(int $reservationId): array;
    function cancelAndRejectReservationsByClassroom(int $classroomId): array;
    function getAllReservationsByClassroom(int $classromId): array;
}