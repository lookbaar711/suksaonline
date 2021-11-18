<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Auth;
use Carbon\Carbon;
class SessionTimeout
{

  public function handle($request, Closure $next)
  {
    // If user is not logged in...
    if (!Auth::guard('members')->user()) {
      return $next($request);
    }

    $user = Auth::guard('members')->user();

    $now = Carbon::now();
    // $last_seen = Carbon::parse($user->last_action_at);

    // $absence = $now->diffInMinutes($last_seen);

    // If user has been inactivity longer than the allowed inactivity period
    if ($user->online_status == '0') {

        Auth::guard('members')->logout();
        $request->session()->invalidate();

        return redirect('/');
    }

    $user->last_action_at = $now->format('Y-m-d H:i:s');
    $user->save();

    return $next($request);
  }

}
