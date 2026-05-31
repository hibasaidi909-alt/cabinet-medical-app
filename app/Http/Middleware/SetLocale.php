<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // إذا كان المستخدم عزل شي لغة غانخبيوها ف الـ Session
    if (session()->has('locale')) {
        App::setLocale(session()->get('locale'));
    }
    
    return $next($request);
}
}
