<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Call;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Exception;


class Approvecampaign_Controller extends Controller
{
 		public function approve_campaign_list(Request $request)
    {
       	Log::channel('custom_log')->info('approve campaign function started.');

				// Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);
	
				// Determine the user's role
    		$userRole = Auth::user()->user_master_id;

    		// Build the query based on the user's role

        Log::channel('custom_log')->info('User:' . auth()->user()->name);
				// User is admin, include user_name
			
				$query = DB::select('CALL approve_campaign()');

				if ($request->ajax()) 
				{

					Log::channel('custom_log')->info('Approve campaign displays data.', ['query' => $query]);
	
            // Create a DataTables response for the query results
            return Datatables::of($query)
						->addIndexColumn()
					       /*	->addColumn('action', function($query)
						{
								$actionButtons = '';
								$callStatus = $query->call_status;

								if ($callStatus == 'C') 
								{
										$approveBtn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="select_sender(\'' . $query->campaign_name . '\', \'' . $query->mobile_numbers . '\')">Approve</a>';
	                                
										$declineBtn = '<a href="javascript:void(0)" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="campaign_decline(\'' . $query->campaign_name . '\', ' . $query->mobile_numbers . ', ' . $query->user_id . ', \'' . $query->user_name . '\', \'' . $query->context . '\')">Decline</a>';


										$actionButtons = '<div class="btn-group">' . $approveBtn . '&nbsp;' . $declineBtn . '</div>';
								} 

            		return $actionButtons;
						})*/

						->addColumn('action', function($query)
						{
								$actionButtons = '';
								$callStatus = $query->call_status;

								if ($callStatus == 'C') 
								{
									$downloadbtn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-300 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="download_function(\'' . $query->mobile . '\')">Download</a>';

										$approveBtn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-400 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="select_sender(\'' . $query->campaign_name . '\', \'' . $query->mobile_numbers . '\')">Approve</a>';
	                                
										$declineBtn = '<a href="javascript:void(0)" class="md:w-full bg-gray-500 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="campaign_decline(\'' . $query->campaign_name . '\', ' . $query->mobile_numbers . ', ' . $query->user_id . ', \'' . $query->user_name . '\', \'' . $query->context . '\')">Decline</a>';

                                                                         $actionButtons = '<div class="btn-group">' . $downloadbtn . '&nbsp;' . $approveBtn . '&nbsp;' . $declineBtn . '</div>';

								} 

            		return $actionButtons;
						})  
					
            ->make(true);

        }
				// Render the view for the campaign list
        return view('approve_campaign');
    }


		public function get_sender_count(Request $request)
		{

				$sender_ids = $request->input('server_ids');

				$serverNeronMap = []; // Map to store server_id => neron_id

				// Fetch neron_ids corresponding to server_ids
				$neronIds = DB::table('neron_masters')
						->whereIn('server_id', $sender_ids)
						->get(['server_id', 'neron_id']); // Fetch server_id and neron_id

				foreach ($neronIds as $neronId) 
				{
						$serverNeronMap[$neronId->neron_id] = $neronId->server_id;
				}

				$serverChannelCounts = [];

				foreach ($neronIds as $neronId) 
				{
						$simCount = DB::table('channel_status')
														->where('server_id', $neronId->neron_id)
														->where('state', 'idle')
														->count();

						// Map neron_id to server_id and store the count for each server_id
						$server_id = $serverNeronMap[$neronId->neron_id];
						$serverChannelCounts[$server_id] = $simCount;
				}

				// Prepare the response based on individual counts
				$response = [];
				foreach ($serverChannelCounts as $server_id => $count) 
				{
						$response[] = [
								'server_id' => $server_id,
								'free_channels' => $count
						];
				}
				return response()->json(['success' => true, 'server_counts' => $response]);
		}

		public function get_sender_id(Request $request)
		{

				$sender_id = DB::table('neron_masters')
                ->where('neron_status', 'Y')
                ->get();

        		return response()->json(['success' => true, 'sender_id' => $sender_id]);

		}


		public function decline_campaign(Request $request)
		{

				$campaignName = $request->input('campaign_name');

				$mobileCount = $request->input('no_mob_no');

				$userId = $request->input('user_id');

				$userName = $request->input('user_name');

				$context =$request->input('context');

				$remarks =$request->input('remarks');

				$decline = DB::table('calls')
						->where('campaign_name', $campaignName)
						->update(['call_status' => 'D', 'remarks' => $remarks]);
	
				// Update the 'user_credits' table
        		$update_credits = DB::table('user_credits')
                ->where('user_id', $userId)
               	->decrement('used_credits', $mobileCount);

	
				if ($decline) 
				{
        		return response()->json(['message' => 'Campaign Declined']);
    		} 
				else 
				{
        		return response()->json(['message' => 'Campaign Decline Failed'], 400);
    		}
		}


		public function approve_campaign_send(Request $request)
		{
				$campaignName = $request->input('campaign_name');
				$sender_ids = $request->input('server_ids');
				$cam_percentage = $request->input('cam_percentage');

				// $nodeApiUrls = DB::table('neron_masters')
				// ->whereIn('server_id', $sender_ids)
				// ->pluck('node_port')
				// ->toArray();
			
				// echo "###".json_encode($sender_ids)."###".json_encode($nodeApiUrls)."***".json_encode($cam_percentage)."$$$"; exit;

				// Fetch campaign records from the 'calls' table
    		$campaignRecords = Call::where('campaign_name', $campaignName)->first();

    		if ($campaignRecords) 
				{
						$campaign_id = $campaignRecords->campaign_id;
						$sender_id = $campaignRecords->neron_id;
        				$user_id = $campaignRecords->userId;
						
						$api_user_id =  Auth::id();

						$currentYear = date('Y');
						$julianDate = date('z') + 1;
						$currentTime = date('His');
						$uniqueSerialNumber = mt_rand(10, 99);

						$request_id = "{$api_user_id}_{$currentYear}{$julianDate}{$currentTime}_{$uniqueSerialNumber}";
					
					// Remove square brackets and quotations
						$sender_ids_cleaned = implode(',', $sender_ids);
												
						$senderId_data = array(
							'neron_id' => $sender_ids_cleaned,
						);

						DB::table('calls')
							->where('campaign_id', $campaign_id)
							->update($senderId_data);

						// $query = 
						// [
							// 		"campaign_id" => $campaign_id,
							// 		"user_id" => $user_id,
							// 		"api_user_id" => $api_user_id,
							// 		"request_id" => $request_id,
						// ];

						// $query = 
						// [
	    				// 	"campaign_id" => $campaign_id,
            			// 	"user_id" => $user_id,
						// 	"sender_id" => $sender_ids,
						// 	"campaign_percentage" => $cam_percentage,
        				// ];

						//$queryJson = json_encode($query);
	
						//get the api url from config/app.php
        				// $nodeApiUrl = config('app.api_url');
						
						$nodeApiUrls = DB::table('neron_masters')
							->select('node_port')
							->whereIn('server_id',$sender_ids)
							->get();


						if (count($cam_percentage) != count($nodeApiUrls) ) {
								// Handle mismatch between counts
								// You can throw an error, log it, or handle it based on your application's needs
								return response()->json(['success' => false, 'message' => ' Selected Sender ID count not exceed '], 201);
						}

						

        		        try   
						{
        				// Send the queryArray to the Node.js API
        				// $response = Http::post($nodeApiUrl, $query);
						// 	// 	$response = Http::post($nodeApiUrl, [
						// 	// 		'json' => $queryJson, // Send the JSON string as 'json' data in the request
						// 	// ]);

						// $apiResponse = $response->body();

						// $Response_data = json_decode($apiResponse);

						// Loop through both arrays simultaneously
						$query = [
							"campaign_id" => $campaign_id,
							"user_id" => $user_id,
							"sender_id" => $sender_ids,
							"campaign_percentage" => $cam_percentage,
						];

						// Initialize an empty array to hold the formatted data
						$formattedData = [];

						// Combine sender IDs and campaign percentages into an array of objects
						for ($i = 0; $i < count($sender_ids); $i++) {
							$formattedData[] = [
								$sender_ids[$i] => $cam_percentage[$i]
							];
						}

						// Encode the array into JSON format
						$jsonData = json_encode($formattedData);

						// echo "###".json_encode($sender_ids)."***".json_encode($cam_percentage)."$$$".$jsonData; exit;
						

						foreach ($nodeApiUrls as $nodeApiUrl) {
							// Check if there's a corresponding percentage for the current URL
							// if (isset($cam_percentage[$index])) {
								// $Cam_Percentage = $cam_percentage[$index];
						
								$query = [
									"campaign_id" => $campaign_id,
									"user_id" => $user_id,
									"sender_id" => $sender_ids,
									"campaign_percentage" => $formattedData,
								];

								// echo "###".$nodeApiUrl->node_port."###";

								// echo "$$$".json_encode($query)."$$$"; exit;
						
								// Post data to the current node API URL
								$response = Http::post($nodeApiUrl->node_port, $query); // Pass query as an array
						
								$apiResponse = $response->body();
								
								// Process $apiResponse as needed
								$Response_data = json_decode($apiResponse);
								
								// Do something with $Response_data
							// } else {
							// 	// Handle the case where there's no corresponding percentage for the current URL
							// 	// You can throw an error, log it, or handle it based on your application's needs
							// 	return response()->json(['success' => false, 'message' => 'Campaign percentage not available'], 201);
							// }
						}

						// return response()->json(['success' => true, 'message' => 'Campaign Started Successfully', 'data' => $apiResponse], 200);
						
						// if ($response->successful()) 
						// 		{
						// 		$apiResponse = $response->body();

						// 		//log file
						// 		Log::channel('custom_log')->info('API Response: ' . $apiResponse);

						// 		return response()->json(['success' => true, 'message' => 'Campaign Started Successfully', 'data' => $apiResponse]);
						//          } 
						// 		else 
						// 		{
						// 	// Handle the error response
						// 	return response()->json(['success' => false, 'message' => 'Failed to Start the campaign', 'status' => $response->status()]);

						// }

					

						if ($Response_data->response_status === 200) {
							Log::channel('custom_log')->info('API Response: ' . $apiResponse);

							return response()->json(['success' => true, 'message' => 'Campaign Started Successfully', 'data' => $apiResponse], 200);

					} elseif ($Response_data->response_status === 202) {

							$neronIds = $Response_data->neronIds;

							Log::channel('custom_log')->info('Active Neron Ids details: ' . $neronIds);

							Log::channel('custom_log')->info('API Response: ' . $apiResponse);

							return response()->json(['success' => true, 'message' => 'Un Available neron ids : ', 'data' => $apiResponse, 'neron_ids' => $neronIds], 202);

					} else {
							return response()->json(['success' => false, 'message' => 'Failed to Start the campaign', 'status' => $response->status(), 'value' => gettype($apiResponse)], 201);
					}
			
						}
					
    				 
						catch (Exception $e) 
						{
        				// Handle exceptions, e.g., network errors
								return response()->json(['success' => false, 'message' => 'Error Sending Data to start the campaign: ' . $e->getMessage()]);

    					}
    		}
    		else 
    		{
        		// Handle the case where no campaign records were found
        		return response()->json(['success' => false, 'message' => 'No campaign records found for the specified campaign_name.']);

    		}
	
		}


}


