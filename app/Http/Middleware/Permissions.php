<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Service\ServiceImplementation\PersonServiceImpl as PersonService;

class Permissions
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
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!empty($permissions)) {
            $data = [
                'person_id' => $request->session_id,
                'permissions' => $permissions
            ];

            $havePermission = $this->personService->havePermission($data);
            
            if (!$havePermission) {
                return response()->json(
                    ['status' => 'No tienes permisos suficientes'],
                    403
                );
            }
        }
        return $next($request);
    }
}
