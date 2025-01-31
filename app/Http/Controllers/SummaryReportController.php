<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\cdr;
use App\Models\Call;
use Illuminate\Support\Carbon;
use DataTables;
use DB;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckAuthentication;
use Illuminate\Support\Facades\Schema;


class SummaryReportController extends Controller
{

		//datatable display for summary report
	// 		public function summary_Report(Request $request)
	// {
	// 	// Log the start of the function execution
  //        	Log::channel('custom_log')->info('summary_report function started.');

	// 	// Apply the CheckAuthentication middleware to this method
  //       	$this->middleware(CheckAuthentication::class);

  //   		// Determine the user's role
  //   		$userRole = Auth::user()->user_master_id;

  //       	Log::channel('custom_log')->info('User:' . auth()->user()->name);
	// 	// User is admin, include user_name
	
	// 	// Build the query based on the user's role
  //       	$query = DB::table('summary_reports as sum')
  //               	->leftJoin('calls', 'calls.campaign_id', '=', 'sum.campaign_id');


  // 	      	Log::channel('custom_log')->info('User:' . auth()->user()->name);
  //       	// User is admin, include user_name
  //       	$query->leftJoin('users', 'calls.userId', '=', 'users.id')
  //               	->selectRaw('users.name')
  //               	->selectRaw('calls.campaign_name')
	// 		->selectRaw("DATE_FORMAT(sum.campaign_date, '%d-%m-%Y') AS campaign_date") 
  //               	->selectRaw('COALESCE(sum.total_dialled, 0) AS total_dialled')
  //               	->selectRaw('COALESCE(sum.total_success, 0) AS total_success')
  //               	->selectRaw('COALESCE(sum.total_failed, 0) AS total_failed')
  //               	->selectRaw('COALESCE(sum.total_busy, 0) AS total_busy')
  //               	->selectRaw('COALESCE(sum.total_no_answer, 0) AS total_no_answer')
  //               	->selectRaw('COALESCE(sum.first_attempt, 0) AS first_attempt')
  //               	->selectRaw('COALESCE(sum.retry_1, 0) AS retry_1')
  //               	->selectRaw('COALESCE(sum.retry_2, 0) AS retry_2')
  //               	->selectRaw('COALESCE(sum.success_percentage, 0) AS success_percentage')
  //               	->orderBy('sum.campaign_date', 'DESC');

  //       	if($userRole !== 1)
  //       	{
  //               	$query->where('calls.userId', Auth::id());
  //       	}


	//    	// Add date range filtering
  //   		if (!empty($request->get('summary_to_date')) && !empty($request->get('summary_from_date'))) 
	// 	{
  //       		$summary_from_date = $request->get('summary_from_date');
  //       		$summary_end_date = $request->get('summary_to_date');


	// 		// Display records from the 'cdrs' table
  //                       $query->whereDate('sum.summary_report_entdate', '>=', $summary_from_date)
  //                               ->whereDate('sum.summary_report_entdate', '<=', $summary_end_date);


	// 	}


	// 	// Yajra DataTables AJAX query
  //   		if ($request->ajax()) {

	// 	//Log the generated SQL query
  //       	$sql = $query->toSql();
  //        	Log::channel('custom_log')->info('User_query:' . $sql);


  //       	return Datatables::of($query)
	// 		->addIndexColumn()
	// 		->filter(function ($instance) use ($request, $userRole) 
	// 		{
  //               		if ($request->input('search.value') != "") 
	// 			{
  //                   			// Custom search for 'calldate' and 'campaign'
  //                   			$searchValue = $request->input('search.value');
  //                   			$instance->where(function ($w) use ($searchValue, $userRole) 
	// 				{
	// 					$searchDate = date('Y-m-d', strtotime(str_replace('-', '/', $searchValue)));

  //                       			$w->where('sum.campaign_date', 'like', "%{$searchDate}%")
	// 		  			->orWhere('calls.campaign_name', 'like', "%{$searchValue}%")
	// 					->orWhere('users.name', 'like', "%{$searchValue}%")
	//             				->orWhere('sum.total_dialled', 'like', "%{$searchValue}%")
  //           					->orWhere('sum.total_success', 'like', "%{$searchValue}%")
  //           					->orWhere('sum.total_failed', 'like', "%{$searchValue}%")
	// 					->orWhere('sum.total_busy', 'like', "%{$searchValue}%")
	// 					->orWhere('sum.total_no_answer', 'like', "%{$searchValue}%")
	// 					->orWhere('sum.first_attempt', 'like', "%{$searchValue}%")
	// 					->orWhere('sum.retry_1', 'like', "%{$searchValue}%")
	// 					->orWhere('sum.retry_2', 'like', "%{$searchValue}%")
	// 					->orWhere('sum.success_percentage', 'like', "%{$searchValue}%");


	// 					if ($userRole === 1) 
	// 					{
  //                       				$w->orWhere(function ($subquery) use ($searchValue, $searchDate) 
	// 						{
  //                           					$subquery->select(DB::raw(1))
  //                               					->from('users')
  //                               					->join('calls', 'users.id', '=', 'calls.userId')
	// 								->Where('sum.campaign_date', 'like', "%{$searchDate}%")
	// 		        					->orWhere('calls.campaign_name', 'like', "%{$searchValue}%")
	// 								->orwhere('users.name', 'like', "%{$searchValue}%");

  //                       				});
  //                   				}

  //                   			});
  //               		}
  //           		})
  //           		->make(true);
  //   		}
	
  //   		// Render the view for the summaryreport
  //   		return view('summary_report');
	// } 



				public function summary_Report(Request $request)
        {
                // Log the start of the function execution
                Log::channel('custom_log')->info('summary_report function started.');

                // Apply the CheckAuthentication middleware to this method
                $this->middleware(CheckAuthentication::class);

                Log::channel('custom_log')->info('User:' . auth()->user()->name);

                if (!empty($request->get('summary_to_date')) && !empty($request->get('summary_from_date')))
                {
                        $startDate = Carbon::parse($request->get('summary_from_date'))->format('d-m-Y');
                        $endDate = Carbon::parse($request->get('summary_to_date'))->format('d-m-Y');
                        $userId =  Auth::id();
                        $userRole = Auth::user()->user_master_id;

                        Log::channel('custom_log')->info('Start Date: ' . $startDate . ', End Date: ' . $endDate . ', User ID: ' . $userId);

                        $query = DB::select('CALL summary_report(?, ?, ?)',
                        [
                                $startDate,
                                $endDate,
                                $userId,
                        ]);
                }
								// Yajra DataTables AJAX query
                if ($request->ajax())
                {
                        return Datatables::of($query)
                        ->addIndexColumn()
                        ->make(true);
                }

                // Render the view for the summaryreport
                return view('summary_report');
        }


} 


