<?php
namespace App\Service;

interface ClassroomService
{
    function getAllClassrooms(string $statuses): array;
    function getAllClassroomsWithStatistics(): array;
    function getAllAvailableClassrooms(): array;
    function getClassroomByID(int $id): array;
    function isDeletedClassroom(int $classroomId): bool;
    function getDisponibleClassroomsByBlock(int $blockId): array;
    function getClassroomsByBlock(int $blockId): array;
    function getClassroomByDisponibility(array $data): array;
    function suggestClassrooms(array $data): array;
    function store(array $data): string;
    function update(array $data): string; 
    function disable(int $classroom_id): string;
    function retriveLastClassroom(array $data): array;
    function deleteByClassroomId(int $classroomId): array;
    function getClassroomStats(array $data): array;
}

