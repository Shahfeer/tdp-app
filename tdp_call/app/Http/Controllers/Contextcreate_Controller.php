<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Wav;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckAuthentication;
use App\Models\Call;
use App\Models\PromptMaster;
use App\Models\Master_state;
use App\Models\Master_language;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class Contextcreate_Controller extends Controller
{

	public function context_create(Request $request)
	{

		// Render the view for the campaign list
		return view('context_create');
	}

	public function prompt_create(Request $request)
	{

		Log::channel('custom_log')->info('context create function started.');

		// Apply the CheckAuthentication middleware to this method
		$this->middleware(CheckAuthentication::class);

		$userId = Session::get('user_id');
		$userName = auth()->user()->name;

		$curdtm = date("YmdHi");

		$context_name = $request->input('context_value');

		$remarks = $request->input('remarks');
		$company_name = $request->input('company_name');

		$languageCode = $request->input('language_code');
		$language = Master_language::where('language_code', $languageCode)->select('language_id')->first();

		$locationCode = $request->input('location');
		$location = Master_state::where('state_short_name', $locationCode)->select('id')->first();
		$type = $request->input('type');

		$campaign_type = $request->input('campaign_type');

		$latestIVR = PromptMaster::max('ivr_id');
		$ivr_id = ($latestIVR >= 7001 && $latestIVR < 7999) ? $latestIVR + 1 : 7001;

		//$IVR_id = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

		if ($campaign_type == 'N') {
			$userId = auth()->user()->id;
			$curdtm = date("YmdHis");

			// Upload the MP3 file
			$originalFilename = $request->file('upload_file')->getClientOriginalName();
			//$newFilename = "str_replace(' ', '_', $originalFilename); // Replace spaces with underscores
			// $storedFilenameInDB = str_replace(' ', '_', $originalFilename);
			// Remove spaces and change to lowercase
			$storedFilenameInDB = str_replace(' ', '_', strtolower($originalFilename));

			// Get only the first 50 characters
			$storedFilenameInDB = substr($storedFilenameInDB, 0, 50);

			$filename = "{$curdtm}_obd_call_{$userId}_{$storedFilenameInDB}";

			$user_prompt = $request->file('upload_file')->storeAs('user_prompt_files', $filename);

			// Construct the full path to the user_prompt file
			$baseDirectory = storage_path('app');
			$fullUserPromptPath = "$baseDirectory/$user_prompt";

			$prompt_status = 'N';
		} else {
			$filename = '-';
			$prompt_status = '-';
		}

		// $context = "{$userId}_{$curdtm}_{$context_name}";

		$context = "{$context_name}_{$ivr_id}";
		//dd("!!!");

		// Check if the context already exists
		$existingContext = PromptMaster::where('context', $context)->first();

		if ($existingContext) {
			$existingContext->prompt_path = $filename;
			$existingContext->remarks = $remarks;
			$existingContext->prompt_entry_time = now();
			$existingContext->save();

			$msg = 'Prompt - <span style="color: red;">"' . $context . '"</span> Updated Successfully!!';
		} else {
			$prompt = new PromptMaster([
				'user_id' => $userId,
				'ivr_id' => $ivr_id,
				'company_name' => $company_name,
				'campaign_type' => $campaign_type,
				'states_id' => $location->id,
				'language_id' => $language->language_id,
				'type' => $type,
				'prompt_path' => $filename,
				'context' => $context,
				'remarks' => $remarks,
				'prompt_status' => $prompt_status,
				'prompt_entry_time' => now(),
			]);

			$prompt->save();

			$msg = 'Prompt - <span style="color: red;">"' . $context . '"</span> Created Successfully!!';
			
		}
// dd($msg);
		// Render the view for the campaign list
		return Redirect::to('context_create')->with('success', $msg);
	}



	public function get_location()
	{
		$locations = DB::table('master_states')
			->select('name', 'state_short_name')
			->whereNotNull('state_short_name')
			->orderBy('name')
			->get();

		return response()->json($locations);
	}


	public function get_language()
	{
		$languages = DB::table('master_languages')
			->select('language_name', 'language_code')
			->orderBy('language_name')
			->get();

		return response()->json($languages);
	}


	public function check_context(Request $request)
	{
		//	echo "!!!!";

		$context = $request->input('context');
		//	echo($context);
		// Query the database to check if the context exists
		$existingContext = PromptMaster::where('context', $context)->first();

		if ($existingContext) {
			// Context exists, return the context name and associated prompt
			$response = [
				'context' => $existingContext->context,
				'prompt_path' => $existingContext->prompt_path,
			];

			return response()->json($response);
		}

		return response()->json(['message' => 'Context does not exist']);
	}
}


