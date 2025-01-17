<?php

namespace App\Http\Controllers;

use App\Models\Neron_master;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Call;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Exception;
// DB::statement("SET SQL_MODE=''");

class Campaignlist_Controller extends Controller
{
	public function campaign_list(Request $request)
  {
  		Log::channel('custom_log')->info('campaign_list function started.');

			// Apply the CheckAuthentication middleware to this method
    	$this->middleware(CheckAuthentication::class);
	

			if (!empty($request->get('detail_to_date')) && !empty($request->get('detail_from_date'))) 
			{
					$startDate = Carbon::parse($request->get('detail_from_date'))->format('d-m-Y');
    			$endDate = Carbon::parse($request->get('detail_to_date'))->format('d-m-Y');
					$userId =  Auth::id();
					$userRole = Auth::user()->user_master_id;

					Log::channel('custom_log')->info('Start Date: ' . $startDate . ', End Date: ' . $endDate . ', User ID: ' . $userId);

					$query = DB::select('CALL campaign_list(?, ?, ?)', 
					[
    					$startDate,
    					$endDate,
    					$userId,
					]);
			}
		
			if ($request->ajax()) 
			{
      		// Create a DataTables response for the query results
      		return Datatables::of($query)
					->addIndexColumn()
					->addColumn('action', function($query)
					{
					//Log::channel('custom_log')->info('call status:' . $query);
					$actionButtons = ' - ';
					$callStatus = $query->status;
					
					if ($callStatus == 'P') 
					{
						$campaign_name = str_replace(['_', ','], '', $query->campaign_name);
						$stop_campaign = '<a href="javascript:void(0)" id = "stop-campaign" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full buttonElement" onclick="stopCampaign(\''. $query->campaign_name . '\')">Stop</a>';
						
						$actionButtons = '<div class="btn-group">'  . $stop_campaign . '</div>';
					}

					if ($callStatus == 'S') 
					{
						$campaign_name = str_replace(['_', ','], '', $query->campaign_name);
						$restart_campaign = '<a href="javascript:void(0)" id = "restart-campaign" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full buttonElement" onclick="restartCampaign(\''. $query->campaign_name . '\')">Restart</a>';
						
						$actionButtons = '<div class="btn-group">'  . $restart_campaign . '</div>';
					} 
						
					return $actionButtons;
					}) 
      		->rawColumns(['action'])
			
      		->make(true);
   		}

			// Render the view for the campaign list
    	return view('campaign_list');
  }


		public function stop_campaign(Request $request)
		{
			$campaignName = $request->input('campaign_name');
			//echo $campaignName;

			$campaignRecords = Call::where('campaign_name', $campaignName)->first();
					
			if ($campaignRecords) 
			{
				$campaign_id = $campaignRecords->campaign_id;
				$user_id = $campaignRecords->userId;
		
				$query = 
				[
						"campaign_id" => $campaign_id,
						"user_id" => $user_id,
				];	

        $nodeApiUrl = config('app.stop_campaign');

        try 
				{
	      	$response = Http::post($nodeApiUrl, $query);
	
        	if ($response->successful()) 
					{
         		$apiResponse = $response->body();

				 		$campaign = Call::where('campaign_name', $campaignName)->first();

				 		
        		//log file
        		Log::channel('custom_log')->info('API Response: ' . $apiResponse);

        		return response()->json(['success' => true, 'message' => 'Campaign Stopped Successfully', 'data' => $apiResponse]);
        	} 
					else 
					{
            // Handle the error response
            return response()->json(['error' => false, 'statusText' => 'Failed to Stop the campaign', 'status' => $response->status()]);
        	}
    		} 
				catch (Exception $e) 
				{
        	// Handle exceptions, e.g., network errors
					return response()->json(['error' => false, 'statusText' => 'Error Sending Data to stop the campaign: ' . $e->getMessage()]);
    		}	
			}
			else 
			{
				// Handle the case where no campaign records were found
				return response()->json(['error' => false, 'statusText' => 'No campaign records found for the specified campaign_name.']);

			}
		}


		public function restart_campaign(Request $request)
		{
			$campaignName = $request->input('campaign_name');
			
			// Fetch campaign records from the 'calls' table
			$campaignRecords = Call::where('campaign_name', $campaignName)->first();
					
			if ($campaignRecords) 
			{
				$campaign_id = $campaignRecords->campaign_id;
				$user_id = $campaignRecords->userId;
		
				$query = 
				[
						"campaign_id" => $campaign_id,
						"user_id" => $user_id,
				];	

        $nodeApiUrl = config('app.restart_campaign');

        try 
				{
        	$response = Http::post($nodeApiUrl, $query);
	
        	if ($response->successful()) 
					{

         		$apiResponse = $response->body();

        		//log file
        		Log::channel('custom_log')->info('API Response: ' . $apiResponse);

        		return response()->json(['success' => true, 'message' => 'Campaign Started Successfully', 'data' => $apiResponse]);
        	} 
					else 
					{
            // Handle the error response
            return response()->json(['success' => false, 'message' => 'Failed to Start the campaign', 'status' => $response->status()]);
        	}
    		} 
				catch (Exception $e) 
				{
        	// Handle exceptions, e.g., network errors
					return response()->json(['success' => false, 'message' => 'Error Sending Data to Start the campaign: ' . $e->getMessage()]);
    		}
			}
			else 
			{
				// Handle the case where no campaign records were found
				return response()->json(['error' => false, 'statusText' => 'No campaign records found for the specified campaign_name.']);

			}
		}


		public function neron_details(Request $request)
    {
        $neronId = $request->input('neron_id'); // Retrieve neron_id from the request

		// Fetch neron_details based on the provided neron_id
		$neronDetails = DB::table('neron_masters')
		->where('neron_id', $neronId)
		->first(); // Assuming it's a single detail
        
        // You can modify the logic based on your data structure

        if ($neronDetails) 
				{
            return response()->json(['neron_details' => $neronDetails], 200);
        } 
				else 
				{
            return response()->json(['message' => 'Neron details not found'], 404);
        }
    }

}
