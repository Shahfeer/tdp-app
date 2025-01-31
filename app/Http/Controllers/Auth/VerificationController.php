<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */


   public function sendOTP(Request $request)
   {
   	 $user = User::where('email', $request->email)->first();

    	if (!$user) 
	{
        	return response()->json(['error', 'Incorrect email. Please enter the correct email.']);
    	}

    	$otp = Str::random(6); // Generate a random 6-digit OTP

    	// Store OTP in the cache with a 15-minute expiration time
    	//Cache::put('password_reset_' . $user->email, $otp, 15);

	// Store the OTP in the session with a key
	session(['password_reset_otp' => $otp]);


	// Send the OTP to the user's email using Laravel's Mail
	Mail::to($user->email)->send(new PasswordResetMail($otp)); // Replace with your mail implementation
	
	// Set a session variable to indicate that OTP is sent
	  Session::put('otp_sent', true);
	 return response()->json(['email' => $user->email]);
   }


public function verifyOtp(Request $request) {

    $request->validate([
        'email' => 'required|email',
        'otp' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();
	
    if (!$user) {
        // Handle the case where the user doesn't exist
        return redirect()->route('forgot-password')->with('error', 'Invalid email address.');
    }
	
    //$storedOtp = Cache::get('password_reset_' . $user->email);

    // Retrieve the OTP from the session
    $storedOtp = session('password_reset_otp');
	

    if ($storedOtp == $request->otp) {

	// Clear the OTP from the session
        session()->forget('password_reset_otp');

        // The OTP doesn't match
	return redirect()->route('password-reset', ['email' => $user->email]);
    }
   else
   {
    // OTP is correct, redirect to the password reset form
    return redirect()->route('forgot-password')->with('otp-error', 'Incorrect OTP. Please enter the correct OTP.');

   }
}


   public function ForgotPassword()
   {
   	 return view('auth.forgot-password');
   }
}
