<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\{
    PersonRepository,
    NotificationTypeRepository
};
use App\Service\AuthService;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthServiceImpl implements AuthService
{
    private $notificationService;
    private $mailService;
    private $personRepository;
    private $personService;

    public function __construct()
    {
        $this->notificationService = new NotificationServiceImpl();
        $this->mailService = new MailerServiceImpl();

        $this->personRepository = new PersonRepository();
        $this->personService = new PersonServiceImpl();
    }

    /**
     * Function to register a new user with personal data
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        return $this->personService->store($data);
    }

    /**
     * Function to recover password of an person
     * @param array $data
     * @return array
     */
    public function resetPassword(array $data): array
    {
        $person = $this->personService->getUserByEmail($data['email']);
        $claims = [
            'type' => 'access',
            'faculty_id' => -1
        ];
        $token = JWTAuth::claims($claims)->fromUser($person);
        $data['token'] = $token;
        $this->mailService->sendResetPassword($data);
        return ['message' => 'Se le envio un correo para que pueda recuperar su contraseña'];
    }

    /**
     * Function to change the password of the authenticated person
     * @param array $data
     * @return array
     */
    public function changePassword(array $data): array
    {
        $this->notificationService->store([
			'title' => 'CAMBIO DE CONTRASEÑA',
            'body' => 
                "Se cambio la contraseña desde el siguiente dispositivo: "
                .$data['agent']['device']
                ." desde el navegador: "
                .$data['agent']['browser'],
            'type' => NotificationTypeRepository::warning(),
            'sendBy' => personRepository::system(),
            'to' => [$data['person_id']],
			'sended' => 1,
		]);
        $this->personRepository->changePassword($data);
        return ['message' => 'La contraseña fue cambiada con exito'];
    }
}