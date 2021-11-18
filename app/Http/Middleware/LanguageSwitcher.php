<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
        }
        else{
            App::setLocale('th');
        };

        return $next($request);
    }
}
