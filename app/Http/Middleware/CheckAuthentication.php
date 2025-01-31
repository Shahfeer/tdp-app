<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class CheckAuthentication
{

    public function handle(Request $request, Closure $next)
    {

        if (!Auth::check()) 
	    {
            Log::channel('custom_log')->info('User is not authenticated. Redirecting to login.');
            return redirect()->route('login');
        }

        $login_time = session('login_time');
        $user_id = Auth::id();

        $dbUserStatus = DB::table('user_logs')
        ->where('user_id', $user_id)
        ->where('user_log_status', 'I')
        ->where('login_time', $login_time)
        ->exists();


        if (!$dbUserStatus) 
        {
            $request->session()->flush();

            Log::channel('custom_log')->info('User session and DB login time mismatch. Redirecting to login.');

            // Redirect to login page or any other action you need
            return redirect()->route('login');
        }
	
	    return $next($request);

   }
    
}
