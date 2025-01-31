<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\cdr;
use App\Models\Call;
use App\Models\User;
use App\Models\UserCredit;
use Illuminate\Support\Carbon;
use DataTables;
use DB;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckAuthentication;


class HomeController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    // Log the start of the function execution
    Log::channel('custom_log')->info('dashboard function started.');

    // Apply the CheckAuthentication middleware to this method
    $this->middleware(CheckAuthentication::class);

    $userId = Auth::id(); // Get the authenticated user's ID
    $userMasterId = Auth::user()->user_master_id; // Get the user's role (user_master_id)

    // Initialize data variables
    $adminData = [];
    $adminDatas = []; // Initialize $adminDatas

    // Call the stored procedures
    $datas = DB::select("CALL dashboard($userId)");
    $data1 = DB::select("CALL dashboard_six($userId)");

    if (!empty($datas) && is_array($datas)) {
        $adminData = $datas; // Accessing the admin data if returned
    }

    if (!empty($data1) && is_array($data1)) {
        $adminDatas = $data1; // Accessing the admin datas if returned
        
    }
// dd($adminData);

    // Return both adminData and adminDatas to the view
    return view('home', compact('adminData', 'adminDatas'));
}

    public function callfile()  
    {
         return view('file-import');
    }

}   



