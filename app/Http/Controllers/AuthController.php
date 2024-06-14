<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\{
    Person,
    User
};
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{
    Auth,
    Validator
};

class AuthController extends Controller
{
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


    public function authenticate(Request $request): Response
    {

        $credentials = $request->only('email','password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:50'
        ]);

        if ($validator->fails()) {
            $message = implode('.',$validator->errors()->all());
            return response()->json(
                ['message' => $message],
                400
            );
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
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

        return response()->json(
            [
                'token' => $token,
                'user' => [
                    'user_id' => $user->id,
                    'person_id' => $person->id,
                    'name' => $person->name,
                    'last_name' => $person->last_name,
                    'email' => $person->email,
                ]
            ],
            200
        );
    }

    public function logout(Request $request):Response
    {

        $data = $request->only('token');
        
        $validator = Validator::make($data,[
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
            JWTAuth::invalidate($request->token);
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
}
