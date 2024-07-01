<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\{
    JWTException,
    TokenInvalidException,
    TokenExpiredException
};
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{
    Auth,
    Validator
};

use App\Service\ServiceImplementation\{
    PersonServiceImpl
};

class AuthController extends Controller
{
    private $personService;

    public function __construct()
    {
        $this->personService = new PersonServiceImpl();
    }
    /**
     * Function to register people
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {

        $validator = $this->validatePersonData($request);

        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $value)
                $message = $message . $value . '.';
            return response()->json(
                ['message' => $message],
                400
            );
        }

        $data = $validator->validated();

        $person = $this->personService->store($data);

        $credentials = [
            'id' => $person['id'],
            'email' => $person['email'],
            'password' => $data['password']
        ];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(
                    ['message' => 'Error token no generado'], 
                    401
                );
            }
        } catch (JWTException $e) {
            return response()->json(
                [
                    'message' => 'Error en el servidor',
                    'error' => $e->getMessage()
                ], 
                500
            );
        }

        return response()->json(
            [
                'message' => 'Usuario creado exitosamente!',
                'token' => $token,
                'person' => [
                    'person_id' => $person['id'],
                    'name' => $person['name'],
                    'last_name' => $person['last_name'],
                    'email' => $person['email']
                ]
            ]
            ,201
        );
    }

    /**
     * Validate all data of person
     * @param Request $request
     * @return mixed
     */
    private function validatePersonData(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'user_name' => 'required|string|unique:people',
            'email' => 'required|email|unique:people',
            'password' => 'required|string|min:8|max:50',
        ],[
            'name.required' => 'El/Los nombre/s del usuario es obligatorio',

            'last_name.required' => 'El/Los apellido/s del usuario es obligatorio',

            'user_name.required' => 'El nick name ya se encuentra en uso',
            'user_name.unique' => 'El nick name no esta disponible',

            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Ingrese un correo valido',
            'email.unique' => 'El correo ya se encuentra registrado',

            'password.required' => 'La contraseña es obligatoria',
            'password.string' => 'La contraseña tiene que tener un formato valido',
            'password.min' => 'La cantidad de caracteres minima para una contraseña es 8',
            'password.max' => 'La cantidad de caracteres maxima para una contraseña es 50'
        ]);
    }

    /**
     * Handle a login request to the application
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {

        $validator = $this->validateLoginData($request);

        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $value)
                $message = $message . $value . '.';
            return response()->json(
                ['message' => $message],
                400
            );
        }

        $data = $validator->validated();

        $login = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email':'user_name';

        $credentials = [
            $login => $data['login'],
            'password' => $data['password']
        ];

        try {
            if (!$token = JWTAuth::claims(['type' => 'access'])->attempt($credentials)) {
                return response()->json(
                    ['message' => 'Login fallido'],
                    401
                );
            }
        } catch (JWTException $e) {
            return response()->json(
                [
                    'message' => 'Error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }

        $person = Auth::user();
        $roles = $this->personService->getRoles($person->id);

        JWTAuth::factory()->setTTL(config('jwt.refresh_ttl'));
        $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($person);

        return response()->json(
            [
                'token' => $token,
                'refresh_token' => $refreshToken,
                'user' => [
                    'person_id' => $person->id,
                    'name' => $person->name,
                    'last_name' => $person->last_name,
                    'email' => $person->email,
                    'roles' => $roles
                ]
            ],
            200
        );
    }

    /**
     * Validate all data of person
     * @param Request $request
     * @return mixed
     */
    private function validateLoginData(Request $request)
    {
        return Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string|min:8|max:50'
        ], [
            'login.required' => 'Ingrese su correo o nombre de usuario',

            'password.required' => 'La contraseña es obligatoria',
            'password.string' => 'La contraseña tiene que tener un formato valido',
            'password.min' => 'La cantidad de caracteres minima para una contraseña es 8',
            'password.max' => 'La cantidad de caracteres maxima para una contraseña es 50'
        ]);
    }

    /**
     * 
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request):Response
    {

        $accessToken = $request->bearerToken();
        $refreshToken = $request->header('Refresh_token');
        
        if (!$accessToken) {
            return response()->json(
                ['message' => 'Token de acceso no proporcionado'],
                400
            );
        }

        if (!$refreshToken) {
            return response()->json(
                ['message' => 'Token de refresco no proporcionado'],
                400
            );
        }

        try {
            JWTAuth::setToken($accessToken);
            if (JWTAuth::getPayload()->get('type') === 'access') {
                JWTAuth::invalidate($accessToken);
            } else {
                return response()->json(
                    ['message' => 'Token de acceso inválido'], 
                    401
                );
            }
            JWTAuth::setToken($refreshToken);
            if (JWTAuth::getPayload()->get('type') === 'refresh') {
                JWTAuth::invalidate($refreshToken);
            } else {
                return response()->json(
                    ['message' => 'Token de refresco inválido'], 
                    401
                );
            }
            
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Usuario desconectado'
                ],
                200
            );
        } catch (TokenExpiredException $e) {
            return response()->json(
                ['message' => 'Token ya expirado'],
                401
            );
        } catch (TokenInvalidException $e) {
            return response()->json(
                ['message' => 'Token inválido'],
                401
            );
        } catch (JWTException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error al invalidar el token',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * 
     * @param Request $request
     * @return Response
     */
    public function getUser(Request $request):Response
    {

        $data = $request->only('token');

        $validator = Validator::make($data, [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            $message = implode('.', $validator->errors()->all());
            return response()->json(
                ['message' => $message], 
                400
            );
        }

        try {
            $user = JWTAuth::authenticate($request->token);

            if (!$user) {
                return response()->json(
                    ['message' => 'Token es invalido o expiro'], 
                    401
                );
            }

            return response()->json(
                ['user' => $user], 
                200
            );
        } catch (JWTException $e) {
            return response()->json(
                [
                    'message' => 'Error en el servidor', 
                    'error' => $e->getMessage()
                ], 
                500
            );
        }
    }

    /**
     * Function to verify the validity of a token
     * @param Request $request
     * @return Response
     */
    public function tokenStatus(Request $request):Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
            return response()->json(
                ['status' => 'Token valido'], 
                200
            );
            
        } catch (JWTException $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(
                    ['status' => 'Token invalido'], 
                    401
                );
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(
                    ['status' => 'Token expirado'], 
                    401
                );
            } else {
                return response()->json(
                    ['status' => 'Token no encontrado',
                    'error' => $e->getMessage()], 
                    401
                );
            }
        }
    }

    /**
     * Refresh the JWT token.
     * @param Request $request
     * @return Response
     */
    public function tokenRefresh(Request $request): Response
    {
        try {
            $refreshToken = $request->bearerToken();
            JWTAuth::setToken($refreshToken);
            if (JWTAuth::getPayload()->get('type') !== 'refresh') {
                return response()->json(
                    ['message' => 'Token de refresco inválido'], 
                    401
                );
            }
            $newToken = JWTAuth::claims(['type' => 'access'])->refresh($refreshToken);
            return response()->json(
                ['token' => $newToken], 
                200
            );
        } catch (TokenExpiredException $e) {
            return response()->json(
                ['message' => 'Token de refresco expirado'],
                401
            );
        } catch (TokenInvalidException $e) {
            return response()->json(
                ['message' => 'Token de refresco inválido'], 
                401
            );
        } catch (JWTException $e) {
            return response()->json(
                [
                    'message' => 'Error al refrescar el token',
                    'error' => $e->getMessage()
                ], 
                500
            );
        }
    }
}
