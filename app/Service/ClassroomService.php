<?php
namespace App\Service;

use App\Models\Classroom;

interface ClassroomService
{
    function getAllClassrooms(): array;
    function availableClassroomsByBlock(int $blockId): array;
    function getClassroomsByBlock(int $blockId): array;
    
    function store(array $data): string;
}

