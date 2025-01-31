<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\cdr;
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
public function summary_Report(Request $request)
{
	// Log the start of the function execution
         Log::channel('custom_log')->info('summary_report function started.');

	// Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);

    // Determine the user's role
    $userRole = Auth::user()->user_master_id;

	if($userRole === 1) {
        Log::channel('custom_log')->info('User:' . auth()->user()->name);
	// User is admin, include user_name
	
	$query = DB::table('calls')
    ->select([
        DB::raw('DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") AS calldates'),
        'calls.campaign_name',
	'users.name as user_name',
        DB::raw('COUNT(*) AS count_total'),
        DB::raw('COUNT(*) AS count_dialled_total'),
        DB::raw('SUM(CASE WHEN cdrs.disposition = "answered" THEN 1 ELSE 0 END) AS count_success'),
        DB::raw('SUM(CASE WHEN cdrs.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END) AS count_failure'),
        DB::raw('ROUND(((SUM(CASE WHEN cdrs.disposition = "answered" THEN 1 ELSE 0 END) * 100) / (SUM(CASE WHEN cdrs.disposition = "answered" THEN 1 ELSE 0 END) + SUM(CASE WHEN cdrs.disposition IN ("no answer","busy", "failed") THEN 1 ELSE 0 END))), 2) AS count_success_percentage'),
        DB::raw('ROUND(AVG(billsec), 0) AS average_aht'),
    ])
    ->leftJoin('cdrs', 'cdrs.campaignId', '=', 'calls.campaign_id')
    ->leftJoin('users', 'calls.userId', '=', 'users.id')
    ->groupBy('calldates', 'calls.campaign_name')
    ->orderBy('calldate', 'desc');
}
	else {
        Log::channel('custom_log')->info('User:' . auth()->user()->name);
	 // User is not admin, select specific columns
	
	$query = DB::table('calls')
    ->select([
        DB::raw('DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") AS calldates'),
        'calls.campaign_name',
        DB::raw('COUNT(*) AS count_total'),
        DB::raw('COUNT(*) AS count_dialled_total'),
        DB::raw('SUM(CASE WHEN cdrs.disposition = "answered" THEN 1 ELSE 0 END) AS count_success'),
        DB::raw('SUM(CASE WHEN cdrs.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END) AS count_failure'),
        DB::raw('ROUND(((SUM(CASE WHEN cdrs.disposition = "answered" THEN 1 ELSE 0 END) * 100) / (SUM(CASE WHEN cdrs.disposition = "answered" THEN 1 ELSE 0 END) + SUM(CASE WHEN cdrs.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END))), 2) AS count_success_percentage'),
        DB::raw('ROUND(AVG(billsec), 0) AS average_aht'),
    ])
    ->leftJoin('cdrs', 'cdrs.campaignId', '=', 'calls.campaign_id')
    ->leftJoin('users', 'calls.userId', '=', 'users.id')
    ->groupBy('calldates', 'calls.campaign_name')
    ->orderBy('calldate', 'desc')
    ->where('calls.userId', Auth::id());

	}

    $query->where('cdrs.report_status', 'Y');

    // Add date range filtering
    if (!empty($request->get('summary_to_date')) && !empty($request->get('summary_from_date'))) {
        $summary_from_date = $request->get('summary_from_date');
        $summary_end_date = $request->get('summary_to_date');

	$currentDate = now()->format('Y-m-d');

    	// Check if either the from date or to date is the current date
    	if ($summary_from_date === $currentDate) 
    	{
		Log::channel('custom_log')->info("From Date: $summary_from_date and End Date: $summary_end_date");

	        $query->whereDate('cdrs.calldate', '>=', $summary_from_date)
        	    ->whereDate('cdrs.calldate', '<=', $summary_end_date);
	}

	else
	{
		if($summary_from_date != $currentDate && $summary_end_date != $currentDate)
		{
			
			// Loop through the date range
        		$currentDate = Carbon::parse($summary_from_date);
        		$endDate = Carbon::parse($summary_end_date);
			
			Log::channel('custom_log')->info("From Date: $summary_from_date and End Date: $summary_end_date");

			$subquery = null;

        		while ($currentDate->lte($endDate)) 
			{

        			$tableName = 'cdrs_' . $currentDate->format('d_m_Y');
				
				Log::channel('custom_log')->info("cdrs tableName Loops - $tableName");

				if (Schema::hasTable($tableName)) 
				{
					if ($userRole === 1) 
					{
						Log::channel('custom_log')->info('User:' . auth()->user()->name);	

						$currentSubquery = DB::table('calls')
						->select([
        					DB::raw('DATE_FORMAT(' .$tableName. '.calldate, "%d-%m-%Y") AS calldates'),
        					'calls.campaign_name',
						'users.name as user_name',
        					DB::raw('COUNT(*) AS count_total'),
        					DB::raw('COUNT(*) AS count_dialled_total'),
        					DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) AS count_success'),
        					DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END) AS count_failure'),
        					DB::raw('ROUND(((SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) * 100) / (SUM(CASE WHEN ' . $tableName . ' .disposition = "answered" THEN 1 ELSE 0 END) + SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer","busy", "failed") THEN 1 ELSE 0 END))), 2) AS count_success_percentage'),
        					DB::raw('ROUND(AVG(billsec), 0) AS average_aht'),
    						])
						->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
    						->leftJoin('users', 'calls.userId', '=', 'users.id')
    						->groupBy('calldates', 'calls.campaign_name')
    						->orderBy('calldate', 'desc');
					}
					else 
					{
        					Log::channel('custom_log')->info('User:' . auth()->user()->name);
	 					// User is not admin, select specific columns
	
						$currentSubquery = DB::table('calls')
						->select([
        					DB::raw('DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") AS calldates'),
        					'calls.campaign_name',
        					DB::raw('COUNT(*) AS count_total'),
        					DB::raw('COUNT(*) AS count_dialled_total'),
        					DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) AS count_success'),
        					DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END) AS count_failure'),
        					DB::raw('ROUND(((SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) * 100) / (SUM(CASE WHEN ' . $tableName . ' .disposition = "answered" THEN 1 ELSE 0 END) + SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END))), 2) AS count_success_percentage'),
        					DB::raw('ROUND(AVG(billsec), 0) AS average_aht'),
    						])
						->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
    						->leftJoin('users', 'calls.userId', '=', 'users.id')
    						->groupBy('calldates', 'calls.campaign_name')
    						->orderBy('calldate', 'desc')
    						->where('calls.userId', Auth::id());

					}
					$currentSubquery->where($tableName.'.report_status', 'Y'); 

	        			$subquery = $subquery ? $subquery->union($currentSubquery) : $currentSubquery;
				}
				else
				{
					Log::channel('custom_log')->info("Table - $tableName does not exit");
				}
			
				$currentDate->addDay();
			}
			$query = $subquery;
		}
		else
		{
			Log::channel('custom_log')->info("From Date: $summary_from_date and End Date: $summary_end_date");

			// If detail_end_date is the current date, loop from detail_from_date to detail_end_date - 1
	        	$currentDate = Carbon::parse($summary_from_date);
            		$endDate = Carbon::parse($summary_end_date)->subDay(); // Subtract one day from end_date

			while ($currentDate->lte($endDate))
                	{

                        	$tableName = 'cdrs_' . $currentDate->format('d_m_Y');
				
				Log::channel('custom_log')->info("cdrs tableName Loops - $tableName");

				if (Schema::hasTable($tableName))
				{                        	
					if ($userRole === 1)
                        		{
						Log::channel('custom_log')->info('User:' . auth()->user()->name);

						$currentSubquery = DB::table('calls')
						->select([
                                        	DB::raw('DATE_FORMAT(' . $tableName . '.calldate, "%d-%m-%Y") AS calldates'),
                                        	'calls.campaign_name',
                                        	'users.name as user_name',
                                        	DB::raw('COUNT(*) AS count_total'),
                                        	DB::raw('COUNT(*) AS count_dialled_total'),
                                        	DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) AS count_success'),
                                        	DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END) AS count_failure'),
                                        	DB::raw('ROUND(((SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) * 100) / (SUM(CASE WHEN ' . $tableName . ' .disposition = "answered" THEN 1 ELSE 0 END) + SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END))), 2) AS count_success_percentage'),
                                        	DB::raw('ROUND(AVG(billsec), 0) AS average_aht'),
                                        	])
						->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
                                        	->leftJoin('users', 'calls.userId', '=', 'users.id')
                                        	->groupBy('calldates', 'calls.campaign_name')
                                        	->orderBy('calldate', 'desc');
                        		}
                        		else
                        		{
                                		Log::channel('custom_log')->info('User:' . auth()->user()->name);
                                		// User is not admin, select specific columns

						$currentSubquery = DB::table('calls')
						->select([
                                        	DB::raw('DATE_FORMAT(' . $tableName. ' .calldate, "%d-%m-%Y") AS calldates'),
                                        	'calls.campaign_name',
                                        	DB::raw('COUNT(*) AS count_total'),
                                        	DB::raw('COUNT(*) AS count_dialled_total'),
                                        	DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) AS count_success'),
                                        	DB::raw('SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END) AS count_failure'),
                                        	DB::raw('ROUND(((SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) * 100) / (SUM(CASE WHEN ' . $tableName . '.disposition = "answered" THEN 1 ELSE 0 END) + SUM(CASE WHEN ' . $tableName . '.disposition IN ("no answer", "busy", "failed") THEN 1 ELSE 0 END))), 2) AS count_success_percentage'),
                                        	DB::raw('ROUND(AVG(billsec), 0) AS average_aht'),
                                        	])
						->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
                                        	->leftJoin('users', 'calls.userId', '=', 'users.id')
                                        	->groupBy('calldates', 'calls.campaign_name')
                                        	->orderBy('calldate', 'desc')
                                        	->where('calls.userId', Auth::id());

                        		}
					$currentSubquery->where($tableName.'.report_status', 'Y');

			 		$subquery = $query->union($currentSubquery);
				}
				else
				{
					Log::channel('custom_log')->info("Table - $tableName does not exit");
				}
				$currentDate->addDay();
			}
			$query = $subquery;
		}
    	}

}  


    // Yajra DataTables AJAX query
    if ($request->ajax()) {

//	 Log the generated SQL query
        $sql = $query->toSql();
         Log::channel('custom_log')->info('User_query:' . $sql);


        return Datatables::of($query)
			->filter(function ($instance) use ($request, $userRole) {
                if ($request->input('search.value') != "") {
                    // Custom search for 'calldate' and 'campaign'
                    $searchValue = $request->input('search.value');
                    $instance->where(function ($w) use ($searchValue, $userRole) {
                        $w->where('cdrs.calldate', 'like', "%{$searchValue}%")
			  ->orWhere('calls.campaign_name', 'like', "%{$searchValue}%");


			if ($userRole === 1) {
                        $w->orWhere(function ($subquery) use ($searchValue) {
                            $subquery->select(DB::raw(1))
                                ->from('users')
                                ->join('calls', 'users.id', '=', 'calls.userId')
				->Where('cdrs.calldate', 'like', "%{$searchValue}%")
			        ->orWhere('calls.campaign_name', 'like', "%{$searchValue}%")
				->orwhere('users.name', 'like', "%{$searchValue}%");

                        });
                    }

                    });
                }
            })
            ->make(true);
    }
	
    // Render the view for the summaryreport
    return view('summary_report');
}





}   
