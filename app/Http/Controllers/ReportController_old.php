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
use DateTime;
use DateInterval;
use DatePeriod;

class ReportController extends Controller
{
    
//datatable display for detail report
    public function detailReport(Request $request)
    {   
	// Log the start of the function execution
	 Log::channel('custom_log')->info('detail_report function started.');

	// Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);

	   // Determine the user's role
	   $userRole = Auth::user()->user_master_id;

     // Build the query based on the user's role
     $query = DB::table('cdrs')
        ->leftJoin('calls', function ($join) {
            $join
		//->on('cdrs.dst', '=', 'calls.mobile')
                ->on('cdrs.campaignId', '=', 'calls.campaign_id');
        });


    if ($userRole === 1) {
        Log::channel('custom_log')->info('User:' . auth()->user()->name);
        // User is admin, include user_name
        $query->leftJoin('users', 'calls.userId', '=', 'users.id')
            ->selectRaw('users.name as user_name')
	    ->selectRaw('calls.campaign_name')
	    ->selectRaw('cdrs.clid')
	    ->selectRaw('cdrs.dst')
	    ->selectRaw('cdrs.src')
	    ->selectRaw('cdrs.disposition')
	    ->selectRaw('cdrs.billsec')
	    ->selectRaw('cdrs.retry_count')
	    ->selectRaw('cdrs.last_call_time')
	    ->selectRaw('cdrs.hangupdate')
	    ->selectRaw('calls.context')
	    ->selectRaw('cdrs.calldate as calldate')
	    //->orderBy('calldate', 'DESC');
	    ->orderBy('cdrs.id', 'DESC');

    } else {
        Log::channel('custom_log')->info('User:' . auth()->user()->name);
        // User is not admin, select specific columns
        $query->selectRaw('calls.campaign_name')
	      ->selectRaw('cdrs.clid')
	      ->selectRaw('cdrs.dst')
	      ->selectRaw('cdrs.src')
	      ->selectRaw('cdrs.disposition')
	      ->selectRaw('cdrs.billsec')
	      ->selectRaw('cdrs.retry_count')
	      ->selectRaw('calls.context')
	      ->selectRaw('cdrs.last_call_time')
            ->selectRaw('cdrs.hangupdate')
	      ->selectRaw('cdrs.calldate as calldate')
	      ->orderBy('cdrs.id', 'DESC')
            ->where('calls.userId', Auth::id());
    }

$query->where('cdrs.report_status', 'Y');


if (!empty($request->get('detail_to_date')) && !empty($request->get('detail_from_date'))) 
{
    $detail_from_date = $request->get('detail_from_date');
    $detail_end_date = $request->get('detail_to_date');
    
    $currentDate = now()->format('Y-m-d');

    // Check if either the from date or to date is the current date
    if ($detail_from_date === $currentDate) 
    {
	Log::channel('custom_log')->info("From Date: $detail_from_date and End Date: $detail_end_date");

        // Display records from the 'cdrs' table
        $query->whereDate('cdrs.calldate', '>=', $detail_from_date)
            ->whereDate('cdrs.calldate', '<=', $detail_end_date);
    } 
    else 
    {
	if($detail_from_date != $currentDate && $detail_end_date != $currentDate)
	{
        	// Loop through the date range
        	$currentDate = Carbon::parse($detail_from_date);
        	$endDate = Carbon::parse($detail_end_date);

		Log::channel('custom_log')->info("From Date: $detail_from_date and End Date: $detail_end_date");

		$subquery = null;

        	while ($currentDate->lte($endDate)) 
		{
			
        		$tableName = 'cdrs_' . $currentDate->format('d_m_Y');

			Log::channel('custom_log')->info("cdrs tableName Loops - $tableName");

			if (Schema::hasTable($tableName)) 
			{

				// Build the query based on the user's role
				$currentSubquery = DB::table($tableName)
        			->leftJoin('calls', function ($join) use ($tableName) {
            			$join
	                	->on($tableName.'.campaignId', '=', 'calls.campaign_id');
        			});

				if ($userRole === 1) 
				{
					Log::channel('custom_log')->info('User:' . auth()->user()->name);		
	
					$currentSubquery->leftJoin('users', 'calls.userId', '=', 'users.id')
            				->selectRaw('users.name as user_name')
	    				->selectRaw('calls.campaign_name')
            				->selectRaw($tableName.'.clid')
            				->selectRaw($tableName.'.dst')
            				->selectRaw($tableName.'.src')
            				->selectRaw($tableName.'.disposition')
            				->selectRaw($tableName.'.billsec')
            				->selectRaw($tableName.'.retry_count')
            				->selectRaw($tableName.'.last_call_time')
            				->selectRaw($tableName.'.hangupdate')
            				->selectRaw('calls.context')
            				->selectRaw($tableName.'.calldate as calldate')
			       		->orderBy($tableName.'.id', 'DESC');
				}
				else 
				{
					Log::channel('custom_log')->info('User:' . auth()->user()->name);

					$currentSubquery->selectRaw('calls.campaign_name')
            				->selectRaw($tableName.'.clid')
            				->selectRaw($tableName.'.dst')
            				->selectRaw($tableName.'.src')
            				->selectRaw($tableName.'.disposition')
            				->selectRaw($tableName.'.billsec')
            				->selectRaw($tableName.'.retry_count')
					->selectRaw('calls.context')
            				->selectRaw($tableName.'.last_call_time')
            				->selectRaw($tableName.'.hangupdate')
            				->selectRaw($tableName.'.calldate as calldate')
		                	->orderBy($tableName.'.id', 'DESC')
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
		Log::channel('custom_log')->info("From Date: $detail_from_date and End Date: $detail_end_date");

		// If detail_end_date is the current date, loop from detail_from_date to detail_end_date - 1
	        $currentDate = Carbon::parse($detail_from_date);
            	$endDate = Carbon::parse($detail_end_date)->subDay(); // Subtract one day from end_date

		while ($currentDate->lte($endDate))
                {

                        $tableName = 'cdrs_' . $currentDate->format('d_m_Y');
			
			Log::channel('custom_log')->info("cdrs tableName Loops - $tableName");

			if (Schema::hasTable($tableName))
                        {

                        	// Build the query based on the user's role
                        	$currentSubquery = DB::table($tableName)
                        	->leftJoin('calls', function ($join) use ($tableName) {
                        	$join
                        	//->on('cdrs.dst', '=', 'calls.mobile')
                        	->on($tableName.'.campaignId', '=', 'calls.campaign_id');
                        	});

                        	if ($userRole === 1)
                        	{
					Log::channel('custom_log')->info('User:' . auth()->user()->name);

                                	$currentSubquery->leftJoin('users', 'calls.userId', '=', 'users.id')
                                	->selectRaw('users.name as user_name')
                                	->selectRaw('calls.campaign_name')
                                	->selectRaw($tableName.'.clid')
                                	->selectRaw($tableName.'.dst')
                                	->selectRaw($tableName.'.src')
                                	->selectRaw($tableName.'.disposition')
                                	->selectRaw($tableName.'.billsec')
                                	->selectRaw($tableName.'.retry_count')
                                	->selectRaw($tableName.'.last_call_time')
                                	->selectRaw($tableName.'.hangupdate')
                                	->selectRaw('calls.context')
                                	->selectRaw($tableName.'.calldate as calldate')
                               		->orderBy($tableName.'.id', 'DESC');
                        	}
                        	else
                        	{
					Log::channel('custom_log')->info('User:' . auth()->user()->name);
					
                                	$currentSubquery->selectRaw('calls.campaign_name')
                                	->selectRaw($tableName.'.clid')
                                	->selectRaw($tableName.'.dst')
                                	->selectRaw($tableName.'.src')
                                	->selectRaw($tableName.'.disposition')
                                	->selectRaw($tableName.'.billsec')
                                	->selectRaw($tableName.'.retry_count')
					->selectRaw('calls.context')
                                	->selectRaw($tableName.'.last_call_time')
                               	 	->selectRaw($tableName.'.hangupdate')
                                	->selectRaw($tableName.'.calldate as calldate')
                                	->orderBy($tableName.'.id', 'DESC')
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


	//yajra datatable ajax query
	if ($request->ajax()) {
	
       	 // Log the generated SQL query
         $sql = $query->toSql();	
         Log::channel('custom_log')->info('User_query:' . $sql);

	  return Datatables::of($query)
            ->addIndexColumn()
            // Custom search functionality query for detail report datatable
            ->filter(function ($instance) use ($request, $userRole, $detail_from_date, $detail_end_date, $currentDate) 
	    {
                if ($request->input('search.value') != "") 
		{
			 $searchValue = $request->input('search.value');
			if($detail_from_date === $currentDate)
			{

	                    //	$searchValue = $request->input('search.value');
        	            	$instance->where(function ($w) use ($searchValue, $userRole) 
			  	{
                        		$w->where('cdrs.campaignId', 'like', "%{$searchValue}%")
                            			->orWhere('dst', 'like', "%{$searchValue}%")
                            			->orWhere('src', 'like', "%{$searchValue}%")
                            			->orWhere('disposition', 'like', "%{$searchValue}%")
                            			->orWhere('billsec', 'like', "%{$searchValue}%")
                            			->orWhere('context', 'like', "%{$searchValue}%")
			    			->orWhere('calldate', 'like', "%{$searchValue}%")
			    			->orWhere('calls.campaign_name', 'like', "%{$searchValue}%")
						->where('cdrs.report_status', 'Y');

					if ($userRole === 1) 
					{
                        			$w->orWhere(function ($subquery) use ($searchValue) 
						{
                            				$subquery->select(DB::raw(1))
                                			->from('users')
                                			->join('calls', 'users.id', '=', 'calls.userId')
                                			->where('users.name', 'like', "%{$searchValue}%")
			   				->orWhere('calls.campaign_name', 'like', "%{$searchValue}%")
                                			->whereRaw('calls.campaign_id = cdrs.campaignId');
                        			});
                    			}
                    		});
			}
			else if($detail_from_date != $currentDate && $detail_end_date != $currentDate)
			{

				// Loop through the date range
		                $currentDate = Carbon::parse($detail_from_date);
                		$endDate = Carbon::parse($detail_end_date);

                		$search_query = null;

                		while ($currentDate->lte($endDate))
                		{
					$tableName = 'cdrs_' . $currentDate->format('d_m_Y');

					$instance->orWhere(function ($w) use ($searchValue, $userRole, $tableName, $search_query) 
					{
				 		$w->where($tableName . '.campaignId', 'like', "%{$searchValue}%")
	                                               	->orWhere('dst', 'like', "%{$searchValue}%")
                                                	->orWhere('src', 'like', "%{$searchValue}%")
                                                	->orWhere('disposition', 'like', "%{$searchValue}%")
                                                	->orWhere('billsec', 'like', "%{$searchValue}%")
                                                	->orWhere('context', 'like', "%{$searchValue}%")
                                                	->orWhere('calldate', 'like', "%{$searchValue}%")
                                                	->orWhere('calls.campaign_name', 'like', "%{$searchValue}%")
							->where($tableName . '.report_status', 'Y');

                                        	if ($userRole === 1)
                                        	{
                                                	$w->orWhere(function ($subquery) use ($searchValue, $tableName, $search_query)
                                                	{
                                                        	$subquery->select(DB::raw(1))
                                                        	->from('users')
                                                        	->join('calls', 'users.id', '=', 'calls.userId')
                                                        	->where('users.name', 'like', "%{$searchValue}%")
                                                        	->orWhere('calls.campaign_name', 'like', "%{$searchValue}%")
                                                        	->whereRaw("calls.campaign_id = {$tableName}.campaignId")
								->where($tableName . '.report_status', 'Y');
                                                	});
                                        	}
					
						$w->where($tableName.'.report_status', 'Y');
	
						$search_query = $search_query ? $search_query->union($w) : $w;
					});

					$currentDate->addDay();
				}
				
			}		
             	}
        })

           ->make(true);

        return $dataTable->make(true);
    }
        return view ('detailreport');

    }



 
//datatable display for summary report
public function summaryReport(Request $request)
{
	// Log the start of the function execution
         Log::channel('custom_log')->info('summary_report function started.');

	// Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);

    	// Determine the user's role
	$userRole = Auth::user()->user_master_id;

	if($userRole === 1) 
	{
        	Log::channel('custom_log')->info('User:' . auth()->user()->name);
		// User is admin, include user_name
		$query = DB::table('calls')
    			->select([
        		DB::raw('DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") AS calldates'),
        		'calls.campaign_name',
			'users.name as user_name',
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 1 AND 5 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_1_5'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 6 AND 10 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_6_10'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 11 AND 15 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_11_15'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 16 AND 20 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_16_20'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 21 AND 25 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_21_25'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 26 AND 30 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_26_30'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 31 AND 45 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_31_45'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 46 AND 60 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_46_60'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec > 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_answered'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec = 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_not_answered'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS grand_total'),

    		])
    		->leftJoin('cdrs', 'cdrs.campaignId', '=', 'calls.campaign_id')
    		->leftJoin('users', 'calls.userId', '=', 'users.id')
    		->groupBy('calldates', 'calls.campaign_name')
    		->orderBy('calldate', 'desc');
	}
	else 
	{
        	Log::channel('custom_log')->info('User:' . auth()->user()->name);
	 	// User is not admin, select specific columns

		$query = DB::table('calls')
			->select([
        		DB::raw('DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") AS calldates'),
        		'calls.campaign_name',
       	 		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 1 AND 5 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_1_5'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 6 AND 10 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_6_10'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 11 AND 15 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_11_15'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 16 AND 20 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_16_20'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 21 AND 25 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_21_25'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 26 AND 30 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_26_30'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 31 AND 45 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_31_45'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec BETWEEN 46 AND 60 AND cdrs.disposition = "ANSWERED" AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS call_46_60'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec > 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_answered'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND cdrs.billsec = 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_not_answered'),
        		DB::raw('(SELECT COUNT(campaignId) FROM cdrs LEFT JOIN calls cls ON cdrs.campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND DATE_FORMAT(cdrs.calldate, "%d-%m-%Y") = calldates) AS grand_total'),

    		])
    		->leftJoin('cdrs', 'cdrs.campaignId', '=', 'calls.campaign_id')
    		->leftJoin('users', 'calls.userId', '=', 'users.id')
    		->groupBy('calldates', 'calls.campaign_name')
    		->orderBy('calldate', 'desc')
    		->where('calls.userId', Auth::id());

	}
   
    	$query->where('cdrs.report_status', 'Y');

    	// Add date range filtering
    	if (!empty($request->get('summary_to_date')) && !empty($request->get('summary_from_date'))) 
	{
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
						if($userRole === 1) 
						{
        						Log::channel('custom_log')->info('User:' . auth()->user()->name);
							// User is admin, include user_name
							$currentSubquery = DB::table('calls')
    							->select([
        						DB::raw('DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") AS calldates'),
        						'calls.campaign_name',
							'users.name as user_name',
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 1 AND 5 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_1_5'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 6 AND 10 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_6_10'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 11 AND 15 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_11_15'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 16 AND 20 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_16_20'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 21 AND 25 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_21_25'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 26 AND 30 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_26_30'),
       		 					DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 31 AND 45 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_31_45'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 46 AND 60 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_46_60'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec > 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec = 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_not_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS grand_total'),

    							])
							->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
    							->leftJoin('users', 'calls.userId', '=', 'users.id')
    							->groupBy('calldates', 'calls.campaign_name')
    							->orderBy('calldate', 'desc');
						}

						else
						{
        						Log::channel('custom_log')->info('User:' . auth()->user()->name);
							// User is admin, include user_name
							$currentSubquery = DB::table('calls')
    							->select([
        						DB::raw('DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") AS calldates'),
        						'calls.campaign_name',
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 1 AND 5 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_1_5'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 6 AND 10 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_6_10'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 11 AND 15 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_11_15'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 16 AND 20 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_16_20'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 21 AND 25 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_21_25'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 26 AND 30 AND ' . $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT(' . $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_26_30'),
       		 					DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 31 AND 45 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_31_45'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec BETWEEN 46 AND 60 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_46_60'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec > 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND ' . $tableName . ' .billsec = 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_not_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON ' . $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS grand_total'),

    							])
							//->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
							->leftJoin(DB::raw("$tableName"), 'calls.campaign_id', '=', DB::raw("$tableName.campaignId"))
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
	
						if($userRole === 1) 
						{
        						Log::channel('custom_log')->info('User:' . auth()->user()->name);
							// User is admin, include user_name
							$currentSubquery = DB::table('calls')
    							->select([
        						DB::raw('DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") AS calldates'),
        						'calls.campaign_name',
							'users.name as user_name',
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 1 AND 5 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_1_5'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 6 AND 10 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_6_10'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 11 AND 15 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_11_15'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 16 AND 20 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_16_20'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 21 AND 25 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_21_25'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 26 AND 30 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_26_30'),
       		 					DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 31 AND 45 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_31_45'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 46 AND 60 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_46_60'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec > 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec = 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_not_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS grand_total'),

    							])
							//->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
							->leftJoin(DB::raw("$tableName"), 'calls.campaign_id', '=', DB::raw("$tableName.campaignId"))
    							->leftJoin('users', 'calls.userId', '=', 'users.id')
    							->groupBy('calldates', 'calls.campaign_name')
    							->orderBy('calldate', 'desc');
						}

						else
						{
        						Log::channel('custom_log')->info('User:' . auth()->user()->name);
							// User is admin, include user_name
							$currentSubquery = DB::table('calls')
    							->select([
        						DB::raw('DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") AS calldates'),
        						'calls.campaign_name',
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 1 AND 5 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_1_5'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 6 AND 10 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_6_10'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 11 AND 15 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_11_15'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 16 AND 20 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_16_20'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 21 AND 25 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_21_25'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 26 AND 30 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_26_30'),
       		 					DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 31 AND 45 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_31_45'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec BETWEEN 46 AND 60 AND '. $tableName . ' .disposition = "ANSWERED" AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS call_46_60'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec > 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND '. $tableName . ' .billsec = 0 AND DATE_FORMAT(calldate, "%d-%m-%Y") = calldates) AS call_not_answered'),
        						DB::raw('(SELECT COUNT(campaignId) FROM '.$tableName.' LEFT JOIN calls cls ON '. $tableName . ' .campaignId = cls.campaign_id WHERE cls.campaign_name = calls.campaign_name AND DATE_FORMAT('. $tableName . ' .calldate, "%d-%m-%Y") = calldates) AS grand_total'),

    							])
							//->leftJoin($tableName, 'calls.campaign_id', '=', $tableName . '.campaignId')
							->leftJoin(DB::raw("$tableName"), 'calls.campaign_id', '=', DB::raw("$tableName.campaignId"))
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

	// Log the generated SQL query
       $sql = $query->toSql();

        Log::channel('custom_log')->info('User_query:' . $sql);


        return Datatables::of($query)
            ->addIndexColumn()
            ->rawColumns(['total_call', 'total_success', 'total_failure'])
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
    return view('summaryreport');
}



}   
