<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        app()->setLocale('ar');

        if($request->lang && $request->lang == "en") {

            app()->setLocale('en');
        } else if($request->lang && $request->lang == "sp") {
            app()->setLocale('sp');
        } else {
            app()->setLocale('ar');
        }
        return $next($request);
    }
}
