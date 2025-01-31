<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\UserCredit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use DB;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Set the default user_master_id to 2
        $data['user_master_id'] = 2;
    
        // Prepare user data
        $user_master_id = $data['user_master_id'];
        $name = $data['name'];
        $email = $data['email'];
        $password = Hash::make($data['password']);
    
        // Execute the stored procedure
        $query = DB::select('CALL register(?, ?, ?, ?)', [
            $user_master_id,
            $name,
            $email,
            $password,
        ]);
    
        // Check if the procedure returned a message
        if ($query && isset($query[0]) && isset($query[0]->user_id)) {
            // Ensure $query[0] is an object and user_id exists
            $user = User::find($query[0]->user_id);
        
            if ($user) {
                // Log in the retrieved user instance (uncomment if needed)
                // auth()->login($user);
        
                // Flash success message to the session and pass user_id
                session()->flash('success', 'Registration successful! Welcome, ' . $user->name . '.');
        
                // Redirect to the registration page with success message and user_id
                return redirect()->route('register')->with([
                    'message' => 'Registered successfully! Kindly click Login',
                    'user_id' => $user->id // Pass the user_id
                ]);
            } else {
                // Log error if user not found
                Log::channel('custom_log')->error('User not found for user_id: ' . $query[0]->user_id);
        
                // Throw an exception for handling the missing user scenario
                throw new \Exception('User not found');
            }
        } else {
            // Log error if procedure failed or no valid user_id is returned
            Log::channel('custom_log')->error('Procedure call failed or returned invalid data: ' . json_encode($query));
        
            // Throw an exception for handling the procedure failure
            throw new \Exception('Procedure call failed or invalid data');
        }
        
        
	
// 	// Create a new user instance
//         $user = User::create([
// 	    'user_master_id' => $data['user_master_id'],
//         'name' => $data['name'],
//         'email' => $data['email'],
//         'password' => Hash::make($data['password']),
//     ]);


//     // Create a new UserCredit instance and associate it with the user
//     $userCredit = new UserCredit([
//     'user_id' => $user->id, // Associate with the user ID
//     'total_credits' => 0,
//     'used_credits' => 0,
//     'available_credits' => 0,
//     'expiry_date' => Carbon::now(), // Current date and time
//     'uc_status' => 'Y',
//     'uc_entry_date' => Carbon::now(), // Current date and time
// ]);

// 	// Save the user credit data
//         $userCredit->save();

        // Log in the newly registered user
    //    auth()->login($user);

    // // Store the user ID in the session
    // session()->put('user_id', $user->id);


    // // Return the user instance
    // return $user;

    }

    }