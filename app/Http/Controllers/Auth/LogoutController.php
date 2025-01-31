<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLog;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LogoutController extends Controller
{

    public function logout(Request $request)
    {
        // Update the user log entry with logout time and status
        $userLog = UserLog::where('user_id', Auth::user()->id)
            ->where('user_log_status', 'I')
            ->orderBy('login_time', 'desc')
            ->first();

        if ($userLog) {
            $userLog->update([
                'logout_time' => now(),
                'user_log_status' => 'O', // Assuming 'O' represents logout status.
            ]);
        }

        // Perform the logout
        Auth::logout();

        return redirect('/login');
    }

}
