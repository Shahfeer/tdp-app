<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\cdr;
use Illuminate\Support\Carbon;
use DataTables;
use DB;
use App\Models\Call;
use App\Models\Cdr_report;
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


		if (!empty($request->get('detail_to_date')) && !empty($request->get('detail_from_date'))) 
		{
			$startDate = Carbon::parse($request->get('detail_from_date'))->format('d-m-Y');
    	$endDate = Carbon::parse($request->get('detail_to_date'))->format('d-m-Y');
			$userId =  Auth::id();
			$userRole = Auth::user()->user_master_id;

			Log::channel('custom_log')->info('Start Date: ' . $startDate . ', End Date: ' . $endDate . ', User ID: ' . $userId);

			$query = DB::select('CALL detail_report(?, ?, ?)', 
			[
    				$startDate,
    				$endDate,
    				$userId,
			]);


			// echo "###".json_encode($query)."### ".$startDate. $endDate .' @ '. $userId; exit;
		}

		
		//yajra datatable ajax query
		if ($request->ajax()) 
		{
			return Datatables::of($query)
			->addIndexColumn()
			->addColumn('action', function($query)
			{
				Log::channel('custom_log')->info('call status:' . json_encode($query));
				$actionButtons = '';
				// $data = json_decode($query, true);
				// $firstData = $query->report;
				// Retrieve the values of 'report' and 'campaign_id'
				$cdrs_report_status = $query->report;
				$campaign_id = $query->campaign_id;
				// //	dd($cdrs_report_status);
				if ($cdrs_report_status == 'Y') 
				{
					
					$actionButtons = '<a href="#" class="btn btn-success" onclick="downloadCdr(\'' . $campaign_id . '\')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
					<path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
					<path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
				  </svg></a>';

				} 
				else
				{
					$actionButtons = '<button style="background-color: #91a0fc !important; color:black; border: none; padding: 10px 20px; cursor: none; border-radius: 60px;">CDR Generation in Progress</button>';

				}
					
				return $actionButtons;
			}) 
			->make(true);
		}

		// Render the view for the detail report
		return view ('detailreport');

  	} 


	/*public function detailReport(Request $request)
    	{

        // Log the start of the function execution
        Log::channel('custom_log')->info('detail_report function started.');

        // Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);

        // Determine the user's role
        $userRole = Auth::user()->user_master_id;

        // Build the query based on the user's role
        $query = DB::table('cdrs as cdr')
                ->leftJoin('calls', 'calls.campaign_id', '=', 'cdr.campaignId');


        Log::channel('custom_log')->info('User:' . auth()->user()->name);
        // User is admin, include user_name
				$query->leftJoin('users', 'calls.userId', '=', 'users.id')
				->select([
					'users.name',
					'calls.campaign_name',
					'cdr.dst',
					'cdr.src',
					'cdr.disposition',
					'cdr.retry_count',
					'cdr.billsec',
					'calls.context',
					'cdr.calldate as calldate',
					'cdr.last_call_time',
					'cdr.hangupdate',
				])
				->orderBy('cdr.calldate', 'DESC');
				//->orderBy('cdr.id', 'DESC');

				if($userRole !== 1)
				{
								$query->where('calls.userId', Auth::id());
				}

				$query->where('cdr.report_status', 'Y');


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
												$query->whereDate('cdr.calldate', '>=', $detail_from_date)
																->whereDate('cdr.calldate', '<=', $detail_end_date);
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
																				$currentSubquery = DB::table($tableName .' as cdr')
																											->leftJoin('calls', 'calls.campaign_id', '=', 'cdr.campaignId');



																				Log::channel('custom_log')->info('User:' . auth()->user()->name);

																				$currentSubquery->leftJoin('users', 'calls.userId', '=', 'users.id')
																				->select([
																					'users.name',
																					'calls.campaign_name',
																					'cdr.dst',
																					'cdr.src',
																					'cdr.disposition',
																					'cdr.retry_count',
																					'cdr.billsec',
																					'calls.context',
																					'cdr.calldate as calldate',
																					'cdr.last_call_time',
																					'cdr.hangupdate',
																					])
																					->orderBy('cdr.calldate', 'DESC');

																					if($userRole !== 1)
																					{
																									$currentSubquery->where('calls.userId', Auth::id());
																					}

																					$currentSubquery->where('cdr.report_status', 'Y');

																					$subquery = $subquery ? $subquery->unionAll($currentSubquery) : $currentSubquery;
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
																		$currentSubquery = DB::table($tableName .' as cdr')
																										->leftJoin('calls', 'calls.campaign_id', '=', 'cdr.campaignId');



																		Log::channel('custom_log')->info('User:' . auth()->user()->name);

																		$currentSubquery->leftJoin('users', 'calls.userId', '=', 'users.id')
																		->select([
																			'users.name',
																			'calls.campaign_name',
																			'cdr.dst',
																			'cdr.src',
																			'cdr.disposition',
																			'cdr.retry_count',
																			'cdr.billsec',
																			'calls.context',
																			'cdr.calldate as calldate',
																			'cdr.last_call_time',
																			'cdr.hangupdate',
																	])
																		//->orderBy('cdr.id', 'DESC');
																		->orderBy('cdr.calldate', 'DESC');


																		if($userRole !== 1)
																		{
																						$currentSubquery->where('calls.userId', Auth::id());
																		}

																		$currentSubquery->where('cdr.report_status', 'Y');

																		$subquery = $query->unionAll($currentSubquery);
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
				if ($request->ajax())
				{
	
						//Log the generated SQL query
						$sql = $query->toSql();

						Log::channel('custom_log')->info('User_query:' . $sql);


						return Datatables::of($query)
												->addIndexColumn()
												// Custom search functionality query for detail report datatable
												->filter(function ($instance) use ($request, $userRole) 
												{
														if ($request->input('search.value') != "") 
														{
																		$searchValue = $request->input('search.value');
																		$instance->where(function ($w) use ($searchValue, $userRole) 
																		{
																						$w->where('cdr.campaignId', 'like', "%{$searchValue}%")
																										->orWhere('dst', 'like', "%{$searchValue}%")
																										->orWhere('src', 'like', "%{$searchValue}%")
																										->orWhere('disposition', 'like', "%{$searchValue}%")
																										->orWhere('billsec', 'like', "%{$searchValue}%")
																										->orWhere('context', 'like', "%{$searchValue}%")
																										->orWhere('calldate', 'like', "%{$searchValue}%")
																										->orWhere('calls.campaign_name', 'like', "%{$searchValue}%");

																										if ($userRole === 1)
																										{
																											$w->orWhere(function ($subquery) use ($searchValue) 
																											{
																											$subquery->where('name', 'like', "%{$searchValue}%");
																											});
																										}
																		});
														}
                        })

                        ->make(true);
												// return response()->json(['count' => $queryCount]);  

 				}

				return view ('detailreport');

		} */


		 //Export the data as CSV
		public function exportasCSV(Request $request)
		{
			
			$userRole = Auth::user()->user_master_id;

			// Build the query based on the user's role
			/*$query = DB::table('cdrs as cdr')
							->leftJoin('calls', 'calls.campaign_id', '=', 'cdr.campaignId');


			Log::channel('custom_log')->info('User:' . auth()->user()->name);
			// User is admin, include user_name
			$query->leftJoin('users', 'calls.userId', '=', 'users.id')
			->select([
				'users.name',
				'calls.campaign_name',
				'cdr.dst',
				'cdr.src',
				'cdr.disposition',
				'cdr.retry_count',
				'cdr.billsec',
				'calls.context',
				'cdr.calldate as calldate',
				'cdr.last_call_time',
				'cdr.hangupdate',
				])
			->orderBy('cdr.calldate', 'DESC');
			

			if($userRole !== 1)
			{
						$query->where('calls.userId', Auth::id());
			}

			$query->where('cdr.report_status', 'Y');


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
									$query->whereDate('cdr.calldate', '>=', $detail_from_date)
													->whereDate('cdr.calldate', '<=', $detail_end_date);
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
																	$currentSubquery = DB::table($tableName .' as cdr')
																									->leftJoin('calls', 'calls.campaign_id', '=', 'cdr.campaignId');



																	Log::channel('custom_log')->info('User:' . auth()->user()->name);

																	$currentSubquery->leftJoin('users', 'calls.userId', '=', 'users.id')
																	->select([
																		'users.name',
																		'calls.campaign_name',
																		'cdr.dst',
																		'cdr.src',
																		'cdr.disposition',
																		'cdr.retry_count',
																		'cdr.billsec',
																		'calls.context',
																		'cdr.calldate as calldate',
																		'cdr.last_call_time',
																		'cdr.hangupdate',
																		])
																	->orderBy('cdr.calldate', 'DESC');

																	if($userRole !== 1)
																	{
																			$currentSubquery->where('calls.userId', Auth::id());
																	}

																	$currentSubquery->where('cdr.report_status', 'Y');

																	$subquery = $subquery ? $subquery->unionAll($currentSubquery) : $currentSubquery;
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
																	$currentSubquery = DB::table($tableName .' as cdr')
																									->leftJoin('calls', 'calls.campaign_id', '=', 'cdr.campaignId');



																	Log::channel('custom_log')->info('User:' . auth()->user()->name);

																	$currentSubquery->leftJoin('users', 'calls.userId', '=', 'users.id')
																	->select([
																		'users.name',
																		'calls.campaign_name',
																		'cdr.dst',
																		'cdr.src',
																		'cdr.disposition',
																		'cdr.retry_count',
																		'cdr.billsec',
																		'calls.context',
																		'cdr.calldate as calldate',
																		'cdr.last_call_time',
																		'cdr.hangupdate',
																])
																	->orderBy('cdr.calldate', 'DESC');


																if($userRole !== 1)
																{
																				$currentSubquery->where('calls.userId', Auth::id());
																}

																$currentSubquery->where('cdr.report_status', 'Y');

																$subquery = $query->unionAll($currentSubquery);
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
			} */


			if (!empty($request->get('detail_to_date')) && !empty($request->get('detail_from_date'))) 
			{
						$startDate = Carbon::parse($request->get('detail_from_date'))->format('d-m-Y');
    				$endDate = Carbon::parse($request->get('detail_to_date'))->format('d-m-Y');
						$userId =  Auth::id();
						$userRole = Auth::user()->user_master_id;

						Log::channel('custom_log')->info('Start Date: ' . $startDate . ', End Date: ' . $endDate . ', User ID: ' . $userId);

						$query = DB::select('CALL detail_report(?, ?, ?)',
						[
    						$startDate,
    						$endDate,
    						$userId,
						]);
			}

				//$data = $query->get()->toArray();
				$data = $query;

				Log::channel('custom_log')->info($data);

				$TotalRecords = count($data);
				Log::channel('custom_log')->info('Response Data Count: ' . $TotalRecords);

				$chunkSize = 1000000; // Set the chunk size to 1 million records

	
				if (!empty($data)) 
				{
						$numRecords = count($data);
						$numChunks = ceil($numRecords / $chunkSize);

						$fileLinks = [];

						for ($i = 0; $i < $numChunks; $i++) 
						{
								$start = $i * $chunkSize;
								$chunk = array_slice($data, $start, $chunkSize);

								if (!empty($chunk)) 
								{
										$fileName = 'OBD_CALL_' . ($i + 1) . '.csv';
										$file = fopen($fileName, 'w');

										// Add header row
										fputcsv($file, array_keys((array)$chunk[0]));

										// Add data rows
										foreach ($chunk as $row) 
										{
												fputcsv($file, (array)$row);
										}

										fclose($file);

										$csvContent = file_get_contents($fileName);

										$fileLinks[] = ['filename' => $fileName, 'csv' => $csvContent]; // Adjust path if necessary
								} 
								else 
								{
										// Handle empty chunks
										echo "Empty chunk or no data available";
								}
						}
						return response()->json(['files' => $fileLinks], 200);
						//echo "CSV generation completed!";
				} 
				else 
				{
					return response()->json(['message' => 'No data available'], 404);
				}

			// 	$data = collect($query)->map(function ($item) {
			// 		return (array) $item;
			// })->toArray();

			// $TotalRecords = count($data);
			// echo $TotalRecords;


			// if (!empty($data)) 
			// {

			// 		Log::channel('custom_log')->info('Data retrieved:');

			// 		foreach ($data as $row) 
			// 		{
			// 				Log::channel('custom_log')->info(json_encode($row));
			// 		}

			// 		$csv = implode(',', array_keys($data[0])) . "\n";

			// 		foreach ($data as $row) 
			// 		{
			// 				$csv .= implode(',', $row) . "\n"; // Add rows to the CSV
			// 		}

			// 		$fileName = 'OBD_CALL.csv';

			// 		// Set response headers for CSV file download
			// 		$headers = 
			// 		[
			// 				'Content-Type' => 'text/csv',
			// 				'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
			// 		];
				
			// 		//Return the file download link or any response back to the client
			// 		return response()->json(['filename' => $fileName, 'csv' => $csv], 200, $headers);
			// 	} 
			// 	else 
			// 	{
			// 			// Handle the case when $data is empty
			// 			// For example, return an error response or appropriate message
			// 			return response()->json(['message' => 'No data available'], 404);
			// 	}

		}	


		public function get_download_url(Request $request)
		{
				// Get the campaign_id from the request
				$campaign_id = $request->input('campaign_id');

				// echo "haiii"; exit;

				// Retrieve the download_url from the cdr_reports table
				$cdr_report = Cdr_report::where('campaign_id', $campaign_id)->first();

				// echo "###".$campaign_id."####"; exit;

				if ($cdr_report) {

					$cdr_report_path = '/tdp_call/storage/cdr_report/' . $cdr_report->download_url;
					// Return the download_url in JSON response
					return response()->json(['download_url' => $cdr_report_path]);
			} else {
					// If the campaign_id is valid but download_url is not found, return an error message
					return response()->json(['error' => 'Download URL not found for the given campaign ID.'], 404);
			}
		}

	public function summaryReport(Request $request)
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

			$query = DB::select('CALL call_holding(?, ?, ?)', 
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
	
		// Render the view for the call hoding time report
		return view('summaryreport');
	}


}







