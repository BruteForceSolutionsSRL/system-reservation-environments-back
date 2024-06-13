<?php

namespace App\Http\Middleware;

use App\Models\Person;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\{
    JWTException,
    TokenInvalidException,
    TokenExpiredException
};
use Illuminate\Http\Request;

use App\Service\ServiceImplementation\PersonServiceImpl as PersonService;

class JwtMiddlware
{

    private $personService;
    public function __construct()
    {
        $this->personService = new PersonService();           
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
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
                    ['status' => 'Autorizacion de token no encontrada'], 
                    401
                );
            }
        }

        $person = $user->person;

        $request->merge(['person_id' => $person->id]);
        return $next($request);

        if (!$person) {
            return response()->json(
                ['status' => 'Usuario no tiene un perfil asociado'],
                401
            );
        }

        if ($permission) {
            $data = [
                'person_id' => $person->id,
                'permission' => $permission
            ];
            $havePermission = $this->personService->havePermission($data);
            if (!$havePermission) {
                return response()->json(
                    ['status' => 'No tienes permiso para realizar esta accion'],
                    403
                );
            }
        }
        $request->merge(['person_id' => $person->id]);
        return $next($request);
    }
}
