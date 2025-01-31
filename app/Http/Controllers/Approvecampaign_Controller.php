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
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use ZipArchive;
use App\Jobs\DownloadAudioFileJob;
use App\Jobs\CreateCallFileJob;
use Illuminate\Support\Facades\SSH;


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

		if ($request->ajax()) {

			Log::channel('custom_log')->info('Approve campaign displays data.', ['query' => $query]);

			// Create a DataTables response for the query results
			return Datatables::of($query)
				->addIndexColumn()

				->addColumn('action', function ($query) {
					$actionButtons = '';
					$callStatus = $query->call_status;

					if ($callStatus == 'C') {

						$downloadbtn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-300 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="download_function(\'' . $query->mobile . '\',\'' . $query->audio_url . '\')">Download</a>';
						$approveBtn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-400 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="campaign_approve(\'' . $query->campaign_name . '\', ' . $query->mobile_numbers . ', ' . $query->user_id . ', \'' . $query->user_name . '\', \'' . $query->context . '\')">Approve</a>';

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


	public function decline_campaign(Request $request)
	{

		$campaignName = $request->input('campaign_name');
		$mobileCount = $request->input('no_mob_no');
		$userId = $request->input('user_id');
		$userName = $request->input('user_name');
		$context = $request->input('context');
		$remarks = $request->input('remarks');

		$decline = DB::table('calls')
			->where('campaign_name', $campaignName)
			->update(['call_status' => 'D', 'remarks' => $remarks]);

		// Update the 'user_credits' table
		$update_credits = DB::table('user_credits')
			->where('user_id', $userId)
			->decrement('used_credits', $mobileCount);


		if ($decline) {
			return response()->json(['message' => 'Campaign Declined']);
		} else {
			return response()->json(['message' => 'Campaign Decline Failed'], 400);
		}
	}

	public function approve_campaign_send(Request $request)
	{
		$campaignName = $request->input('campaign_name');
		$sender_ids = $request->input('server_ids');

		// Fetch campaign records from the 'calls' table
		$campaignRecords = Call::where('campaign_name', $campaignName)->first();

		if ($campaignRecords) {
			$campaign_id = $campaignRecords->campaign_id;
			//$sender_id = $campaignRecords->neron_id;
			$user_id = $campaignRecords->userId;

			$api_user_id = Auth::id();

			$currentYear = date('Y');
			$julianDate = date('z') + 1;
			$currentTime = date('His');
			$uniqueSerialNumber = mt_rand(10, 99);

			$request_id = "{$api_user_id}_{$currentYear}{$julianDate}{$currentTime}_{$uniqueSerialNumber}";

			$query =
				[
					"campaign_id" => $campaign_id,
					"user_id" => $user_id,
				];

			//get the api url from config/app.php
			$nodeApiUrl = config('app.api_url');
			//dd($nodeApiUrl);

			try {
				// Send the queryArray to the Node.js API
				$response = Http::post($nodeApiUrl, $query);

				$apiResponse = $response->body();

				$Response_data = json_decode($apiResponse);

				if ($Response_data->response_status === 200) {
					Log::channel('custom_log')->info('API Response: ' . $apiResponse);
					return response()->json(['success' => true, 'message' => $Response_data->response_msg, 'data' => $apiResponse], 200);
				} elseif ($Response_data->response_status === 201) {
					Log::channel('custom_log')->info('API Response: ' . $apiResponse);
					return response()->json(['success' => true, 'message' => $Response_data->response_msg, 'data' => $apiResponse], 201);
				} else {
					return response()->json(['success' => false, 'message' => 'Failed to Start the campaign', 'status' => $response->status(), 'value' => gettype($apiResponse)], 201);
				}

			} catch (Exception $e) {
				// Handle exceptions, e.g., network errors
				return response()->json(['success' => false, 'message' => 'Error Sending Data to start the campaign: ' . $e->getMessage()]);
			}
		} else {
			// Handle the case where no campaign records were found
			return response()->json(['success' => false, 'message' => 'No campaign records found for the specified campaign_name.']);
		}

	}


	// public function approve_campaign_send(Request $request)
	// {

	// 	$campaignName = $request->input('campaign_name');	

	// 	// Fetch campaign records from the 'calls' table
	// 	$campaignRecords = Call::where('campaign_name', $campaignName)->first();

	// 	try   
	// 	{

	// 		if ($campaignRecords) 
	// 		{

	// 			//$mobileNumbers = explode(',', $campaignRecords->mobile);
	// 			$mobileBlobData = $campaignRecords->mobile;

	// 			$decodedMobileNumbers = json_decode($mobileBlobData, true);

	// 			$context = $campaignRecords->context;
	// 			$campaign_type = $campaignRecords->campaign_type;

	// 			// Create directory with campaign context name if not exists
	// 			$directoryPath = public_path($context);
	// 			if (!file_exists($directoryPath)) 
	// 			{
	// 				mkdir($directoryPath, 0777, true);
	// 			}

	// 			// Process data in chunks of 10000
	// 			$chunkedMobileNumbers = array_chunk($decodedMobileNumbers, 10000);

	// 			$zipChunkCounter = 1;
	// 			$totalFilesProcessed = 0;

	// 			//print_r($chunkedMobileNumbers);

	// 			foreach ($chunkedMobileNumbers as $chunk) 
	// 			{
	// 				DB::beginTransaction();

	// 				try 
	// 				{
	// 					//print_r($chunk);
	// 					$mobileData = [];

	// 					// Create call files for each mobile number
	// 					foreach ($chunk as $mobileNumber) 
	// 					{

	// 						// Access individual properties
	// 						//$mobile = $mobileNumber['number'];
	// 						$mobile = 9991;
	// 						$audio_url = isset($mobileNumber['audio_url']) ? $mobileNumber['audio_url'] : null; // Ensure audio_url is properly handled

	// 						$campaign_id = $campaignRecords->campaign_id;
	// 						$caller_id = $campaignRecords->caller_id;
	// 						$user_id = $campaignRecords->userId;
	// 						$retry_count = $campaignRecords->retry_count;
	// 						$retry_time_interval = $campaignRecords->retry_time_interval;

	// 						$todayDate = Carbon::now()->format('Ymdhms');
	// 						$str1 = Str::random(5);

	// 						if($retry_time_interval == 0)
	// 						{
	// 							$str = 'asterisk -rx "channel originate SIP/9991 application playback demo-congrats"';

	// 							//$str= "Channel: SIP/sip1/".$mobile."\r\nContext: ".$context."\r\nExtension: ".$mobile."\r\nCallerId: ".$caller_id."\r\nArchive: Yes\r\nAccount: ".$todayDate.$str1;
	// 						}
	// 						else
	// 						{
	// 							$str = 'asterisk -rx "channel originate SIP/9991 application playback demo-congrats"';

	// 							//$str= "Channel: SIP/sip1/".$mobile."\r\nContext: ".$context."\r\nExtension: ".$mobile."\r\nCallerId: ".$caller_id."\r\nArchive: Yes\r\nMaxRetries: ".$retry_count."\r\nRetryTime: ".$retry_time_interval."\r\nAccount: ".$todayDate.$str1;
	// 						}

	// 						// Append audio_url if it's not empty and campaign_type is 'C'
	// 						if ($campaign_type == 'C' && $audio_url !== null) 
	// 						{
	// 							//print_r("4");
	// 							//$str .= "\r\nSet: AUDIO_FILE1=".$audio_url;
	// 							$str = 'asterisk -rx "channel originate SIP/9991 application playback demo-congrats"';
	// 						}

	// 						$commandsArray[] = $str;

	// 						// Prepare data for insertion into $mobileData array
	// 						$mobileData[] = 
	// 						[
	// 							'campaignId' => $campaign_id,
	// 							'dst' => $mobile,
	// 							'audio_url' => $campaign_type == 'C' ? $audio_url : null, // Insert audio_url only if campaign_type is 'C'
	// 						];

	// 					}	
	// 					// Bulk insert mobile data
	// 					DB::table('cdrs')->insert($mobileData);

	// 					DB::commit();

	// 				} 
	// 				catch (Exception $e) 
	// 				{
	//        				DB::rollback();
	//        				throw $e;   
	//   				}
	// 			}

	// 			$commandsJson = json_encode($commandsArray);
	// 			//dd($commandsArray);

	// 			//dd("!!!!!!");
	// 			// Define the URL of your Node.js server endpoint
	// 			$nodeApiEndpoint = 'http://192.168.1.55:5004/campaign_request';


	// 			// Send JSON array to Node.js API
	// 			$response = Http::post($nodeApiEndpoint, [
	// 				'commands' => $commandsJson,
	// 			]);
	// 			dd("@@@@@@@@");

	// 			// update the call_status to P in calls table
	// 			DB::table('calls')
	// 				->where('campaign_id', $campaign_id) // Assuming 'campaign_id' is the primary key
	// 				->update(['call_status' => 'P']);

	// 			return response()->json(['success' => true, 'message' => 'Call files created successfully.']);
	// 		}
	// 		else 
	// 		{
	// 			// Handle the case where no campaign records were found
	//     		return response()->json(['success' => false, 'message' => 'No campaign records found for the specified campaign_name.']);
	// 		}
	// 	}

	// 	catch (Exception $e) 
	// 	{
	//     	// Handle exceptions, e.g., network errors
	// 		return response()->json(['success' => false, 'message' => 'Error Sending Data to start the campaign: ' . $e->getMessage()]);
	// 	}    		
	// } 



	/*public function approve_campaign_send(Request $request)
				{
						//print_r("starting time: " . date('Y-m-d H:i:s'));

						$campaignName = $request->input('campaign_name');
						
										
						// Fetch campaign records from the 'calls' table
						$campaignRecords = Call::where('campaign_name', $campaignName)->first();

						try   
						{
							
								if ($campaignRecords) 
								{

										//$mobileNumbers = explode(',', $campaignRecords->mobile);
										$mobileBlobData = $campaignRecords->mobile;
									
										$decodedMobileNumbers = json_decode($mobileBlobData, true);
										
										$context = $campaignRecords->context;
										$campaign_type = $campaignRecords->campaign_type;

										//$audioFilesPath = "/var/lib/asterisk/sounds/en/{$context}/"; // Path to store audio files

										// Create directory for the context if it doesn't exist
										// if ($campaign_type == 'C' && !file_exists($audioFilesPath)) 
										// {
												// 		mkdir($audioFilesPath, 0777, true);
										// }

										// Create directory with campaign context name if not exists
										$directoryPath = public_path($context);
										if (!file_exists($directoryPath)) 
										{
												mkdir($directoryPath, 0777, true);
										}

										// Process data in chunks of 10000
										$chunkedMobileNumbers = array_chunk($decodedMobileNumbers, 10000);

										$zipChunkCounter = 1;
										$totalFilesProcessed = 0;

										//print_r($chunkedMobileNumbers);

										foreach ($chunkedMobileNumbers as $chunk) 
										{
												DB::beginTransaction();
												
												try 
												{
														//print_r($chunk);

														$mobileData = [];
														// Create call files for each mobile number
														foreach ($chunk as $mobileNumber) 
														{
														
																// Access individual properties
																//$mobile = $mobileNumber['number'];
																$mobile = 9991;
																$audio_url = isset($mobileNumber['audio_url']) ? $mobileNumber['audio_url'] : null; // Ensure audio_url is properly handled

																// if (!empty($audio_url)) 
																// {
														
																// 	$fileName = basename($audio_url);
																// 	$filePath = $audioFilesPath . $fileName;
							
																// 	//Download audio file from audio_url
																// 	$audioContent = file_get_contents($audio_url);
																// 	file_put_contents($filePath, $audioContent);
																// }
																//print_r("4");
																
																$campaign_id = $campaignRecords->campaign_id;
																$caller_id = $campaignRecords->caller_id;
																$user_id = $campaignRecords->userId;
																$retry_count = $campaignRecords->retry_count;
																$retry_time_interval = $campaignRecords->retry_time_interval;
																

																$todayDate = Carbon::now()->format('Ymdhms');
																$str1 = Str::random(5);
																
																if($retry_time_interval == 0)
																{
																	
																		$str= "Channel: SIP/sip1/".$mobile."\r\nContext: ".$context."\r\nExtension: ".$mobile."\r\nCallerId: ".$caller_id."\r\nArchive: Yes\r\nAccount: ".$todayDate.$str1;
																}
																else
																{
																	
																		$str= "Channel: SIP/sip1/".$mobile."\r\nContext: ".$context."\r\nExtension: ".$mobile."\r\nCallerId: ".$caller_id."\r\nArchive: Yes\r\nMaxRetries: ".$retry_count."\r\nRetryTime: ".$retry_time_interval."\r\nAccount: ".$todayDate.$str1;
																}

																// Append audio_url if it's not empty and campaign_type is 'C'
																if ($campaign_type == 'C' && $audio_url !== null) 
																{
																		//print_r("4");
																		$str .= "\r\nSet: AUDIO_FILE1=".$audio_url;
																}

																$filename = $mobile . $str1;
																File::put("{$directoryPath}/{$filename}.call", $str);

																// Prepare data for insertion into $mobileData array
																	$mobileData[] = 
																[
																			'campaignId' => $campaign_id,
																			'dst' => $mobile,
																			'audio_url' => $campaign_type == 'C' ? $audio_url : null, // Insert audio_url only if campaign_type is 'C'
																];
															
														}	

														// Bulk insert mobile data
														DB::table('cdrs')->insert($mobileData);

														DB::commit();

														$totalFilesProcessed += count($chunk);

														// Create zip file if total number of files processed reaches 50k
														if ($totalFilesProcessed >= 50000 * $zipChunkCounter) 
														{
																$zipFileName = "{$directoryPath}/{$context}_{$zipChunkCounter}.zip";
																$zip = new ZipArchive();
																if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) 
																{
																		// Add all .call files to the zip
																		$files = glob("{$directoryPath}/*.call");
																		foreach ($files as $file) 
																		{
																				$zip->addFile($file, basename($file));
																		}
																		$zip->close();
																		$zipChunkCounter++;
																} 
																else 
																{
																		throw new Exception('Failed to create zip file.');
																}
														}


												} 
												catch (Exception $e) 
												{
									DB::rollback();
									throw $e;
								}
										}


										// Create zip file for remaining call files
										if ($totalFilesProcessed % 50000 != 0) 
										{
												$zipFileName = "{$directoryPath}/{$context}_{$zipChunkCounter}.zip";
												$zip = new ZipArchive();
												if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) 
												{
														// Add remaining .call files to the zip
														$files = glob("{$directoryPath}/*.call");
														foreach ($files as $file) 
														{
																$zip->addFile($file, basename($file));
														}
														$zip->close();
												} 
												else 
												{
														throw new Exception('Failed to create zip file.');
												}
										}

											// Move the zip file to Asterisk outgoing directory
											$outgoingDirectory = '/var/spool/asterisk/outgoing';
											$zipFiles = glob("{$directoryPath}/*.zip");
											foreach ($zipFiles as $zipFile)
											{
															$destination = "{$outgoingDirectory}/" . basename($zipFile);
															if (!rename($zipFile, $destination))
															{
																			throw new Exception('Failed to move zip file to Asterisk outgoing directory.');
															}

															// Unzip the file
															$zip = new ZipArchive;
															if ($zip->open($destination) === TRUE)
															{
																			$zip->extractTo($outgoingDirectory);
																			$zip->close();
															}
															else
															{
																			throw new Exception('Failed to unzip the file.');
															}
											}
										
										// Delete .call files
										$callFiles = glob("{$directoryPath}/*.call");
										foreach ($callFiles as $file) 
										{
												unlink($file);
										}

										// update the call_status to P in calls table
										DB::table('calls')
												->where('campaign_id', $campaign_id) // Assuming 'campaign_id' is the primary key
												->update(['call_status' => 'P']);

										//print_r("Ending time: " . date('Y-m-d H:i:s'));
										
										return response()->json(['success' => true, 'message' => 'Call files created successfully.']);
								}
								else 
							{
								// Handle the case where no campaign records were found
								return response()->json(['success' => false, 'message' => 'No campaign records found for the specified campaign_name.']);
							}
						}
							 
						catch (Exception $e) 
						{
						// Handle exceptions, e.g., network errors
								return response()->json(['success' => false, 'message' => 'Error Sending Data to start the campaign: ' . $e->getMessage()]);
					}    		
				} */





	/*	public function approve_campaign_send(Request $request)
				 {
						 //print_r("starting time: " . date('Y-m-d H:i:s'));

						 $campaignName = $request->input('campaign_name');
						 
										 
						 // Fetch campaign records from the 'calls' table
					 $campaignRecords = Call::where('campaign_name', $campaignName)->first();

						 try   
						 {
							 
								 if ($campaignRecords) 
								 {

										 //$mobileNumbers = explode(',', $campaignRecords->mobile);
										 $mobileBlobData = $campaignRecords->mobile;
									 
										 $decodedMobileNumbers = json_decode($mobileBlobData, true);
										 
							 $context = $campaignRecords->context;
										 $campaign_type = $campaignRecords->campaign_type;

										 // Create directory with campaign context name if not exists
										 $directoryPath = public_path($context);
										 if (!file_exists($directoryPath)) 
										 {
												 mkdir($directoryPath, 0777, true);
										 }

										 // Process data in chunks of 10000
										 $chunkedMobileNumbers = array_chunk($decodedMobileNumbers, 10000);

										 $zipChunkCounter = 1;
										 $totalFilesProcessed = 0;

										 //print_r($chunkedMobileNumbers);

										 foreach ($chunkedMobileNumbers as $chunk) 
										 {
												 DB::beginTransaction();
												 
												 try 
												 {
														 //print_r($chunk);

														 $mobileData = [];
														 $audioUrls = [];

														 foreach ($chunk as $audioUrl) 
														 {
													 //$mobile = $mobileNumber['number'];
													 $audio_url = isset($audioUrl['audio_url']) ? $audioUrl['audio_url'] : null; // Ensure audio_url is properly handled
																 
																 if (!empty($audioUrl['audio_url'])) 
																 {
																	 
																	 $audioUrls[] = ['audio_url' => $audioUrl['audio_url']];
																 }

														 }
														 
														 //Send audio URLs array to Node.js backend through API
														 $response = Http::post('http://localhost:3000/api/upload-audio', [
																	 'context' => $context,
																	 'audio_urls' => json_encode($audioUrls)
														 ]);

														 // Create call files for each mobile number
														 foreach ($chunk as $mobileNumber) 
														 {
														 
																 // Access individual properties
													 $mobile = $mobileNumber['number'];
													 $audio_url = isset($mobileNumber['audio_url']) ? $mobileNumber['audio_url'] : null; // Ensure audio_url is properly handled
																 
																 $campaign_id = $campaignRecords->campaign_id;
																 $caller_id = $campaignRecords->caller_id;
																 $user_id = $campaignRecords->userId;
																 $retry_count = $campaignRecords->retry_count;
																 $retry_time_interval = $campaignRecords->retry_time_interval;
																						 
																 $todayDate = Carbon::now()->format('Ymdhms');
																 $str1 = Str::random(5);

																 
																						 
																 if($retry_time_interval == 0)
																 {
																						 
																		 $str= "Channel: SIP/sip1/".$mobile."\r\nContext: ".$context."\r\nExtension: ".$mobile."\r\nCallerId: ".$caller_id."\r\nArchive: Yes\r\nAccount: ".$todayDate.$str1;
																 }
																 else
																 {
																			 
																		 $str= "Channel: SIP/sip1/".$mobile."\r\nContext: ".$context."\r\nExtension: ".$mobile."\r\nCallerId: ".$caller_id."\r\nArchive: Yes\r\nMaxRetries: ".$retry_count."\r\nRetryTime: ".$retry_time_interval."\r\nAccount: ".$todayDate.$str1;
																 }

																 // Append audio_url if it's not empty and campaign_type is 'C'
																 if ($campaign_type == 'C' && $audio_url !== null) 
																 {
																		 //print_r("4");
																		 $str .= "\r\nSet: AUDIO_FILE1=".$audio_url;
																 }

																 $filename = $mobile . $str1;
																 File::put("{$directoryPath}/{$filename}.call", $str);

																 // Prepare data for insertion into $mobileData array
																 $mobileData[] = 
																 [
																			 'campaignId' => $campaign_id,
																			 'dst' => $mobile,
																			 'audio_url' => $campaign_type == 'C' ? $audio_url : null, // Insert audio_url only if campaign_type is 'C'
																 ];
															 
														 }	

														 // Bulk insert mobile data
														 DB::table('cdrs')->insert($mobileData);

														 DB::commit();

														 $totalFilesProcessed += count($chunk);

														 // Create zip file if total number of files processed reaches 50k
														 if ($totalFilesProcessed >= 50000 * $zipChunkCounter) 
														 {
																 $zipFileName = "{$directoryPath}/{$context}_{$zipChunkCounter}.zip";
																 $zip = new ZipArchive();
																 if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) 
																 {
																		 // Add all .call files to the zip
																		 $files = glob("{$directoryPath}/*.call");
																		 foreach ($files as $file) 
																		 {
																				 $zip->addFile($file, basename($file));
																		 }
																		 $zip->close();
																		 $zipChunkCounter++;
																 } 
																 else 
																 {
																		 throw new Exception('Failed to create zip file.');
																 }
														 }


												 } 
												 catch (Exception $e) 
												 {
									 DB::rollback();
									 throw $e;
								 }
										 }


										 // Create zip file for remaining call files
										 if ($totalFilesProcessed % 50000 != 0) 
										 {
												 $zipFileName = "{$directoryPath}/{$context}_{$zipChunkCounter}.zip";
												 $zip = new ZipArchive();
												 if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) 
												 {
														 // Add remaining .call files to the zip
														 $files = glob("{$directoryPath}/*.call");
														 foreach ($files as $file) 
														 {
																 $zip->addFile($file, basename($file));
														 }
														 $zip->close();
												 } 
												 else 
												 {
														 throw new Exception('Failed to create zip file.');
												 }
										 }
										 
										 // Delete .call files
										 $callFiles = glob("{$directoryPath}/*.call");
										 foreach ($callFiles as $file) 
										 {
												 unlink($file);
										 }

										 // update the call_status to P in calls table
										 DB::table('calls')
												 ->where('campaign_id', $campaign_id) // Assuming 'campaign_id' is the primary key
												 ->update(['call_status' => 'P']);

										 //print_r("Ending time: " . date('Y-m-d H:i:s'));
										 
										 return response()->json(['success' => true, 'message' => 'Call files created successfully.']);
								 }
								 else 
							 {
								 // Handle the case where no campaign records were found
								 return response()->json(['success' => false, 'message' => 'No campaign records found for the specified campaign_name.']);
							 }
						 }
								
						 catch (Exception $e) 
						 {
						 // Handle exceptions, e.g., network errors
								 return response()->json(['success' => false, 'message' => 'Error Sending Data to start the campaign: ' . $e->getMessage()]);
					 }    		
				 } */



	/*	public function approve_campaign_send(Request $request)
				 {
						 //print_r("starting time: " . date('Y-m-d H:i:s'));

						 $campaignName = $request->input('campaign_name');
						 
										 
						 // Fetch campaign records from the 'calls' table
					 $campaignRecords = Call::where('campaign_name', $campaignName)->first();

						 try   
						 {
							 
								 if ($campaignRecords) 
								 {

										 //$mobileNumbers = explode(',', $campaignRecords->mobile);
										 $mobileBlobData = $campaignRecords->mobile;
									 
										 $decodedMobileNumbers = json_decode($mobileBlobData, true);
										 
							 $context = $campaignRecords->context;
										 $campaign_type = $campaignRecords->campaign_type;

										 $audioFilesPath = "/var/lib/asterisk/sounds/en/{$context}/"; // Path to store audio files

							 // Create directory for the context if it doesn't exist
							 if ($campaign_type == 'C' && !file_exists($audioFilesPath)) 
										 {
								 mkdir($audioFilesPath, 0777, true);
							 }

										 // Create directory with campaign context name if not exists
										 $directoryPath = public_path($context);
										 if (!file_exists($directoryPath)) 
										 {
												 mkdir($directoryPath, 0777, true);
										 }

										 // Process data in chunks of 10000
										 $chunkedMobileNumbers = array_chunk($decodedMobileNumbers, 10000);

										 $zipChunkCounter = 1;
										 $totalFilesProcessed = 0;

										 //print_r($chunkedMobileNumbers);

										 foreach ($chunkedMobileNumbers as $chunk) 
										 {
												 DB::beginTransaction();
												 
												 try 
												 {
														 //print_r($chunk);

														 $mobileData = [];
														 
														 // Create call files for each mobile number
														 foreach ($chunk as $mobileNumber) 
														 {
														 
																 // Access individual properties
													 $mobile = $mobileNumber['number'];
													 $audio_url = isset($mobileNumber['audio_url']) ? $mobileNumber['audio_url'] : null; // Ensure audio_url is properly handled
																 
																 $campaign_id = $campaignRecords->campaign_id;
																 $caller_id = $campaignRecords->caller_id;
																 $user_id = $campaignRecords->userId;
																 $retry_count = $campaignRecords->retry_count;
																 $retry_time_interval = $campaignRecords->retry_time_interval;
																						 
																 $todayDate = Carbon::now()->format('Ymdhms');
																 $str1 = Str::random(5);

																 // Enqueue jobs for downloading audio files and creating call files
																 if($campaign_type == 'C')
																 {
																		 DownloadAudioFileJob::dispatch($audio_url, $audioFilesPath);
																 }
														 
														 CreateCallFileJob::dispatch($mobileData, $mobile, $audio_url, $context, $caller_id, $campaign_id, $retry_count, $retry_time_interval, $todayDate, $str1, $campaign_type);
														 }	

														 // Bulk insert mobile data
														 DB::table('cdrs')->insert($mobileData);

														 DB::commit();

														 $totalFilesProcessed += count($chunk);

														 // Create zip file if total number of files processed reaches 50k
														 if ($totalFilesProcessed >= 50000 * $zipChunkCounter) 
														 {
																 $zipFileName = "{$directoryPath}/{$context}_{$zipChunkCounter}.zip";
																 $zip = new ZipArchive();
																 if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) 
																 {
																		 // Add all .call files to the zip
																		 $files = glob("{$directoryPath}/*.call");
																		 foreach ($files as $file) 
																		 {
																				 $zip->addFile($file, basename($file));
																		 }
																		 $zip->close();
																		 $zipChunkCounter++;
																 } 
																 else 
																 {
																		 throw new Exception('Failed to create zip file.');
																 }
														 }
												 } 
												 catch (Exception $e) 
												 {
									 DB::rollback();
									 throw $e;
								 }
										 }


										 // Create zip file for remaining call files
										 if ($totalFilesProcessed % 50000 != 0) 
										 {
												 $zipFileName = "{$directoryPath}/{$context}_{$zipChunkCounter}.zip";
												 $zip = new ZipArchive();
												 if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) 
												 {
														 // Add remaining .call files to the zip
														 $files = glob("{$directoryPath}/*.call");
														 foreach ($files as $file) 
														 {
																 $zip->addFile($file, basename($file));
														 }
														 $zip->close();
												 } 
												 else 
												 {
														 throw new Exception('Failed to create zip file.');
												 }
										 }
										 
										 // Delete .call files
										 $callFiles = glob("{$directoryPath}/*.call");
										 foreach ($callFiles as $file) 
										 {
												 unlink($file);
										 }

										 // update the call_status to P in calls table
										 DB::table('calls')
												 ->where('campaign_id', $campaign_id) // Assuming 'campaign_id' is the primary key
												 ->update(['call_status' => 'P']);

										 //print_r("Ending time: " . date('Y-m-d H:i:s'));
										 
										 return response()->json(['success' => true, 'message' => 'Call files created successfully.']);
								 }
								 else 
							 {
								 // Handle the case where no campaign records were found
								 return response()->json(['success' => false, 'message' => 'No campaign records found for the specified campaign_name.']);
							 }
						 }
								
						 catch (Exception $e) 
						 {
						 // Handle exceptions, e.g., network errors
								 return response()->json(['success' => false, 'message' => 'Error Sending Data to start the campaign: ' . $e->getMessage()]);
					 }    		
				 } */


}
