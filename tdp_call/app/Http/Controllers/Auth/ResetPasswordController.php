<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;



class ResetPasswordController extends Controller
{
   
    public function PasswordReset($email)
    {
        return view('auth.password-reset', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
	     'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/^(?=.*[0-9])(?=.*[!@#$%^&*()])(?=.*[A-Z]).+$/',
        ],
    ], [
        'password.regex' => 'The password must contain at least 8 characters, 1 special character, 1 capital letter, and 1 number.',
        'password.confirmed' => 'The confirm password does not match.',

    ]);


        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Handle the case where the user doesn't exist
            return redirect()->route('password-reset')->with('error', 'Invalid email address.');
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect to a login page or any other desired location
        return redirect()->route('login')->with('success', 'Password reset successful. You can now log in with your new password.');
    }
}

