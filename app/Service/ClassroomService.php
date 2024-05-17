<?php
namespace App\Service;

use DateTime;

interface ClassroomService
{
    function getAllClassrooms(): array;
    function getDisponibleClassroomsByBlock(int $blockId): array;
    function getClassroomsByBlock(int $blockId): array;
    function getClassroomByDisponibility(array $data): array;
    function suggestClassrooms(array $data): array;
    function store(array $data): string;
    function retriveLastClassroom(array $data): array;
}

