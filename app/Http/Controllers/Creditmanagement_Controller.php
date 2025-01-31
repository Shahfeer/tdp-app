<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckAuthentication;
use App\Models\Call;
use App\Models\UserCredit;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class Creditmanagement_Controller extends Controller
{
    public function credit_management(Request $request)
    {
        Log::channel('custom_log')->info('credit_management function started.');

	// Apply the CheckAuthentication middleware to this method
    $this->middleware(CheckAuthentication::class);

    // Get the authenticated user's ID
    $userId = Auth::id();

     Log::channel('custom_log')->info('Logged in user: ' . auth()->user()->name);

    // Get the user master id to check if admin or user
    $userMasterId = Auth::user()->user_master_id;

    Log::channel('custom_log')->info('User master ID: ' . $userMasterId);

    $query = DB::select('CALL credit_management()');

    if ($request->ajax()) 
    {
        
            // Create a DataTables response for the query results
            return Datatables::of($query)
                ->addIndexColumn()
		        ->addColumn('action', function($query)
                {
				    $btn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" style="background-color: #00ee5a !important; color: black !important; line-height: 40px;" onclick="add_credit(' . $query->user_id . ', \'' . $query->user_name . '\', ' . $query->total_credits . ', ' . $query->used_credits . ', ' . $query->available_credits . ')">
                    Add Credit</a>';
			        return $btn;

                })
                ->make(true);
    }

        // Render the view for the campaign list
        return view('credit_management');
    }



// public function add_credit(Request $request)
// {
//     // Validate the request data here as needed

//     // Get the user ID and credit amount from the request
//     $userId = $request->input('user_id');
//     $creditAmount = $request->input('credit_amount');

//     // Perform the database update here
//     // You can use Eloquent ORM to update the credits
//     $userCredit = UserCredit::where('user_id', $userId)->first();

//     // Update the total_credits attribute
//     $userCredit->total_credits += $creditAmount;

//     // Save the changes
//     $userCredit->save(); 

//     // Optionally, you can log the credit update
//     Log::info('Credit updated for user ID ' . $userId);

//     // Redirect back to the original page or wherever you want
//     return redirect()->back()->with('success', 'Credit updated successfully');
// }

public function add_credit(Request $request)
{
    // Validate the request data
    $request->validate([
        'user_id' => 'required|exists:user_credits,user_id',  // Ensure user_id exists in the table
        'credit_amount' => 'required|numeric|min:1',          // Ensure credit amount is valid
    ]);

    // Get the user ID and credit amount from the request
    $userId = $request->input('user_id');
    $creditAmount = $request->input('credit_amount');

    // Find the user's credit details in the 'user_credits' table
    $userCredit = UserCredit::where('user_id', $userId)->first();

    if ($userCredit) {
        // Update the total credits by adding the new credit amount
        $userCredit->total_credits += $creditAmount;

        // Update the available credits by adding the new credit amount
        $userCredit->available_credits += $creditAmount;

        // Save the changes to the database
        $userCredit->save();

        // Optionally log the credit update
        Log::info('Credit updated for user ID ' . $userId);

        // Return success response or redirect
        return redirect()->back()->with('success', 'Credit updated successfully');
    } else {
        // Handle the case where the user credit record is not found
        return redirect()->back()->with('error', 'User credit record not found');
    }
}
}



