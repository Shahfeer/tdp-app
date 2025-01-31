<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate the request
        $this->validator($request->all())->validate();
    
        try {
            // Create the user and log in
            $user = $this->create($request->all());
    
            // Prepare success message
            $successMessage = 'Registered successfully! Welcome, ' . $user->name . '.';
    
            // Redirect back to the registration page with the success message
            // return redirect()->back()->with('message','Registred successfully'); 
            $successMessage = 'Registered successfully! Welcome, ' . $user->name . '.';

// Return the auth.loginblade view with the message
return view('auth.login', ['message' => $successMessage]);

        } catch (\Exception $e) {
            // Handle the exception (e.g., log the error, return an error response)
            return redirect()->back()->withErrors(['registration_error' => $e->getMessage()]);
        }
    }
    
    
    
   

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
