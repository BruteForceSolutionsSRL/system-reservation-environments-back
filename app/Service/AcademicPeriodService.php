<?php
namespace App\Service;

interface AcademicPeriodService
{
    function store(array $data): string;
    function getActiveAcademicPeriod(): array;
    function deactivateActiveAcademicPeriod(): int;
}

