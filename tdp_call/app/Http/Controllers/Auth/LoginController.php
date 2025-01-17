<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLog;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use DB;

class LoginController extends Controller
{


  public function login(Request $request)
  {

				$request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

  			$credentials = $request->only('email', 'password');
 
  			if (Auth::attempt($credentials)) 
				{

					$user = Auth::user();

					if ($user->user_status === 'Y') 
					{
							// Authentication passed, retrieve the user
							$userId = $user->id;
							$ip_address = $request->ip();

							// Execute the stored procedure
							$query = DB::select('CALL login(?, ?)', 
							[
									$userId,
									$ip_address,
							]);

							// Check if the procedure returned a message
							if ($query && isset($query[0]->response_msg)) 
							{
			
								// Check the message for success or failure
								if ($query[0]->response_msg === 'success') 
								{
										Log::channel('custom_log')->info('user log query response: ' . $query[0]->response_msg);

										// Success message
										// Store the login_time in the session
										$request->session()->put('login_time', now());
			
										// Store the user ID in the session
										$request->session()->put('user_id', $user->id);

										// Redirect to the user's dashboard or another page
										return redirect('/land');
								} 
								else 
								{
										Log::channel('custom_log')->info('user log query error response: ' . $query[0]->response_msg);

								}
							} 
							else 
							{

										Log::channel('custom_log')->info('user log query error response: ' . json_encode($query));

							}
					} 
					else 
					{
						Auth::logout();
            // User status is 'N' - Inactive user
						return view('auth.login')->withErrors(['login' => 'Inactive or Not Approved User. Kindly contact your admin!']);

        	}

						
				}
 
  			// Authentication failed, redirect back with errors
				return back()->withErrors(['login' => 'Invalid email or password. Please enter the correct credentials.'])->withInput($request->only('email'));

	}


	public function __construct()
	{
  	  $this->middleware('guest')->except('logout');
	}


	public function showLoginForm()
	{
      return view('auth.login'); // You should have a 'login.blade.php' view in your 'resources/views/auth' folder.
	}

}





