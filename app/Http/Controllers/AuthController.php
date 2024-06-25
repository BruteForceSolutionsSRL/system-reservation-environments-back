<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\{
    Person,
    User
};
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

class AuthController extends Controller
{
    /**
     * 
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {

        $data = $request->only('name','last_name','email','password');

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:50',
        ]);

        if ($validator->fails()) {
            $message = implode('.', $validator->errors()->all());
            return response()->json(
                ['message' => $message],
                400
            );
        }

        $data = $validator->validated();

        $person = Person::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email']
        ]);

        $user = User::create([
            'person_id' => $person->id,
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);


        $credentials = $request->only('email','password');

        try {
            if (!$token = JWTAuth::attempt(array_merge($credentials, ['person_id' => $user->person_id]))) {
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
                'user' => [
                    'user_id' => $user->id,
                    'person_id' => $person->id,
                    'name' => $person->name,
                    'last_name' => $person->last_name,
                    'email' => $person->email,
                ]
            ]
            ,201
        );
    }

    /**
     * 
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {

        $credentials = $request->only('email','password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:50'
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'El correo tiene que tener un formato valido',
            'password.required' => 'La contraseña es obligatoria',
            'password.string' => 'La contraseña tiene que tener un formato valido',
            'password.min' => 'La cantidad de caracteres minima para una contraseña es 8',
            'password.max' => 'La cantidad de caracteres maxima para una contraseña es 50'
        ]);

        if ($validator->fails()) {
            $message = implode('.',$validator->errors()->all());
            return response()->json(
                ['message' => $message],
                400
            );
        }

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

        $user = Auth::user();
        $person = $user->person;
        $roles = $person->roles()->pluck('name');

        JWTAuth::factory()->setTTL(config('jwt.refresh_ttl'));
        $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($user);

        return response()->json(
            [
                'token' => $token,
                'refresh_token' => $refreshToken,
                'user' => [
                    'user_id' => $user->id,
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
     * 
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request):Response
    {

        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(
                ['message' => 'Token no proporcionado'],
                400
            );
        }

        try {
            JWTAuth::invalidate($token);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Usuario desconectado'
                ],
                200
            );
        } catch (JWTException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
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
     * 
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
