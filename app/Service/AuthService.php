<?php
namespace App\Service;

use PharIo\Manifest\Email;

interface AuthService
{
    function recoverPassword(array $data): array;
    function changePassword(array $data): array;
}

