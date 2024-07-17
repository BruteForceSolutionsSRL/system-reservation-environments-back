<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\PersonRepository;
use App\Service\AuthService;


class AuthServiceImpl implements AuthService
{

    private $notificationService;
    private $mailService;
    private $personRepository;

    public function __construct()
    {
        $this->notificationService = new NotificationServiceImpl();
        $this->mailService = new MailerServiceImpl();
        $this->personRepository = new PersonRepository();
    }

    /**
     * Function to recover password of an person
     * @param array $data
     * @return array
     */
    public function recoverPassword(array $data): array
    {
        $this->mailService->sendRecoverPassword($data['email']);
        return ['message' => 'Se le envio un correo, para que pueda recuperar su contraseña'];
    }

    /**
     * Function to change the password of the authenticated person
     * @param array $data
     * @return array
     */
    public function changePassword(array $data): array
    {
        return $this->personRepository->update($data);
    }
}