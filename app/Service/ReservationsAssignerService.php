<?php
namespace App\Service;

interface ReservationsAssignerService
{
    function reassign(array $reservations): void;
}