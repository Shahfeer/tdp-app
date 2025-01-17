<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckAuthentication;
use App\Models\Call;
use App\Models\UserCredit;
use DataTables;
use DB;

class Creditmanagement_Controller extends Controller
{
    public function credit_management(Request $request)
    {
        Log::channel('custom_log')->info('credit_management function started.');

	// Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);

        // Define a function to build the database query
        function buildQuery($request)
        {
            // Get the authenticated user's ID
            $userId = Auth::id();

            Log::channel('custom_log')->info('Logged in user: ' . auth()->user()->name);

            // Get the user master id to check if admin or user
            $userMasterId = Auth::user()->user_master_id;

            Log::channel('custom_log')->info('User master ID: ' . $userMasterId);

            // Initialize the query with the required fields
            $query = DB::table('user_credits')
                ->selectRaw('users.name as user_name, user_credits.total_credits, user_credits.used_credits, user_credits.available_credits, users.id as user_id')
		->join('users', 'user_credits.user_id', '=', 'users.id')
	  	->where('users.user_master_id', '=', 2); 

            return $query;
        }

        if ($request->ajax()) {
            // Call the buildQuery function to construct the query
            $query = buildQuery($request);

            // Create a DataTables response for the query results
            return Datatables::of($query)
                ->addIndexColumn()
		->addColumn('action', function($query){
				$btn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" style="background-color: rgba(55, 65, 81); color: white; line-height: 40px;" onclick="add_credit(' . $query->user_id . ', \'' . $query->user_name . '\', ' . $query->total_credits . ', ' . $query->used_credits . ', ' . $query->available_credits . ')">
        Add Credit</a>';
			     return $btn;

                    })
                ->filter(function ($query) use ($request) {
                    if ($request->input('search.value') != "") {
                        // column values get in query for search filter
                        $query->where('name', 'like', "%" . $request->input('search.value') . "%");
                    }
                })
                ->make(true);
        }

        // Render the view for the campaign list
        return view('credit_management');
    }



public function add_credit(Request $request)
{
    // Validate the request data here as needed

    // Get the user ID and credit amount from the request
    $userId = $request->input('user_id');
    $creditAmount = $request->input('credit_amount');

    // Perform the database update here
    // You can use Eloquent ORM to update the credits
    $userCredit = UserCredit::where('user_id', $userId)->first();

    // Update the total_credits attribute
    $userCredit->total_credits += $creditAmount;

    // Save the changes
    $userCredit->save(); 

    // Optionally, you can log the credit update
    Log::info('Credit updated for user ID ' . $userId);

    // Redirect back to the original page or wherever you want
    return redirect()->back()->with('success', 'Credit updated successfully');
}

}