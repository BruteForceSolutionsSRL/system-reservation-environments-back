<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizationController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $inputData = $request->all(); 
        $inputData = array_map(function ($value) {
            return strip_tags($value);
        }, $inputData);
        $headers = $request->headers->all(); 
        $request->replace(array_merge($inputData, $headers));
        return $next($request);
    }
}
