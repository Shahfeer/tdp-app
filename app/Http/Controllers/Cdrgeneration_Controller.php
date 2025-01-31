<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckAuthentication;
use App\Models\Call;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use App\Models\Cdr_report;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class Cdrgeneration_Controller extends Controller
{

	public function cdr_generation(Request $request)
	{

		// Render the view for the campaign list
		return view('cdr_generation');
	}

	public function cdrs_generation(Request $request)
	{

		Log::channel('custom_log')->info('cdr generation function started.');

		// Apply the CheckAuthentication middleware to this method
		$this->middleware(CheckAuthentication::class);

		$user_id = $request->input('user');
		$campaign_name = $request->input('campaign');

		$campaignIdObject = DB::table('calls')
			->select('campaign_id')
			->where('campaign_name', $campaign_name)
			->where('userId', $user_id)
			->first();


		$campaignId = $campaignIdObject->campaign_id;

		Log::channel('custom_log')->info('User Id: ' . $user_id . ',  Campaign Id: ' . $campaignId);

		// Execute the stored procedure
		$query = DB::select(
			'CALL cdr_generation(?, ?)',
			[
				$user_id,
				$campaignId,
			]
		);

		// dd($query); exit;


		// Check if the procedure returned a message
		if ($query && isset($query[0]->response_msg)) {

			// Check the message for success or failure
			if ($query[0]->response_msg === 'success: CDR Generated Successfully') {
				Log::channel('custom_log')->info('CDR generation query response: ' . $query[0]->response_msg);

				$csvData = [];
				$serialNo = 1;
				foreach ($query as $cdr) {

					// Assuming your CDR data has some structure like 'id', 'column1', 'column2', etc.
					$csvData[] = [
						'No' => $serialNo++,
						'Campaign Name' => $cdr->cdr_campaign_name,
						'Receiver Mobile No' => $cdr->dst,
						//'Sender Mobile No' => $cdr->src,
						'Call Status' => $cdr->disposition,
						'Retry Count' => $cdr->retry_count,
						'Call Duration(In Secs)' => $cdr->billsec,
						'Context' => $cdr->cdr_context,
						'Call Time' => $cdr->last_call_time,
						'Answered Time' => $cdr->answerdate,
						'End Time' => $cdr->hangupdate,
						// Add more columns as needed
					];
				}

				// Create a CSV file
				$csvFileName = $campaign_name . '.csv';
				$csvFilePath = storage_path('cdr_report/' . $csvFileName);
				if (!file_exists(dirname($csvFilePath))) {
					mkdir(dirname($csvFilePath), 0777, true);
					chmod(dirname($csvFilePath), 0777);
				}
				$csv = Writer::createFromPath($csvFilePath, 'w+');
				$csv->insertOne(array_keys($csvData[0])); // Insert header
				$csv->insertAll($csvData);

				// Create a new zip archive
				$zip = new ZipArchive();
				$zipFileName = $campaign_name . '.zip';
				$zipFilePath = storage_path('cdr_report/' . $zipFileName);

				//update the zip file path in cdr_report table
				Cdr_report::where('campaign_id', $campaignId)
					->update(['download_url' => $zipFileName, 'report_status' => 'Y']);

				if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
					return 'Failed to create zip file';
				}

				// Add the CSV file to the zip file
				$zip->addFile($csvFilePath, $csvFileName);

				// Close the zip archive
				$zip->close();

				// Move the CSV file to another folder
				//$newCsvFolderPath = '/opt/lampp/htdocs/obd_call_report_csv/';
				$newCsvFolderPath = config('app.cdr_report_csv_move');
				$newCsvFilePath = $newCsvFolderPath . $csvFileName;

				// echo "###".$newCsvFolderPath."###"; exit;

				// Check if the new folder exists, if not, create it
				if (!file_exists($newCsvFolderPath)) {
					mkdir($newCsvFolderPath, 0777, true);
					chmod($newCsvFolderPath, 0777);
				}

				if (!file_exists($newCsvFolderPath)) {
					$command = "sudo mkdir -m 777 -p \"$newCsvFolderPath\"";
					exec($command, $output, $returnCode);
					
					if ($returnCode !== 0) {
						echo "Error: Unable to create directory.\n";
						// You can add more error handling here if needed
					} else {
						echo "Directory created successfully.\n";
					}
				} else {
					echo "Directory already exists.\n";
				}

				// Move the CSV file to the new folder
				// rename($csvFilePath, $newCsvFilePath);
				$command = "sudo mv \"$csvFilePath\" \"$newCsvFilePath\"";
				exec($command, $output, $returnCode);

				if ($returnCode !== 0) {
					echo "Error: Unable to rename file.\n";
					// You can add more error handling here if needed
				} else {
					echo "File renamed successfully.\n";
				}


				// Provide a download link to the zip file
				$downloadLink = Storage::url($zipFileName);

				// Success message
				$msg = 'CDR Generated Successfully for the campaign - <span style="color: red;">"' . $campaign_name . '"</span>';
				return Redirect::to('cdr_generation')->with('success', $msg);
			} else {
				Log::channel('custom_log')->info('CDR generation query response: ' . $query[0]->response_msg);

				// Failure message
				$msg = 'CDR Generation Failed';
				return Redirect::to('cdr_generation')->with('error', $msg);
			}
		} else {
			Log::channel('custom_log')->info('CDR generation query response: ' . json_encode($query));

			// Handle unexpected result or no message returned
			$msg = 'Something went wrong';
			return Redirect::to('cdr_generation')->with('error', $msg);
		}
	}


	public function get_user()
	{

		$user_data = DB::table('calls')
			->join('users', 'calls.userId', '=', 'users.id')
			//->where('users.user_master_id', '!=', 1)
			->pluck('users.id', 'users.name')
			->toArray();

		if (!empty($user_data)) {
			// You might also want to return the user data as JSON
			return response()->json(['users' => $user_data]);
		}

		return response()->json(['error' => 'User IDs not found']);
	}


	public function get_campaigns(Request $request)
	{
		$selectedUser = $request->input('user');

		$campaigns = Call::select('campaign_name', 'no_of_mobile_numbers', 'call_entry_time')
			->where('userId', $selectedUser)
			->where('cdrs_report', 'N')
			->where('call_status', 'O')
			->orderBy('call_entry_time', 'DESC')
			->get();

		$campaignNames = $campaigns->pluck('campaign_name')->toArray();

		// Transform data to include numbers in campaign_name
		$formattedCampaigns = $campaigns->map(function ($campaign) {
			return $campaign->campaign_name . ' [ ' . $campaign->no_of_mobile_numbers . ' ]';
		});

		return response()->json([
			'campaign_names' => $campaignNames,
			'formatted_campaigns' => $formattedCampaigns
		]);
	}
}
