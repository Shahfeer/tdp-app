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
use DataTables;
use DB;

class Contextcreate_Controller extends Controller
{

			public function context_create(Request $request)
    	{

     		// Render the view for the campaign list
        	return view('context_create');
    	}

			public function prompt_create(Request $request)
  		{

						Log::channel('custom_log')->info('cdr generation function started.');

						// Apply the CheckAuthentication middleware to this method
						$this->middleware(CheckAuthentication::class);

						$userId = Session::get('user_id');
						$userName = auth()->user()->name;

						$curdtm = date("YmdHi");

						$context = $request->input('context_value');

						$remarks = $request->input('remarks');
						$company_name = $request->input('company_name');

						$languageCode = $request->input('language_code');
						$language = Master_language::where('language_code', $languageCode)->select('language_id')->first();

						$locationCode = $request->input('location');
						$location = Master_state::where('state_short_name', $locationCode)->select('id')->first();		
					
						$type = $request->input('type');

						$IVR_id = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

						// Check if the context already exists
						$existingContext = PromptMaster::where('context', $context)->first();
						if ($existingContext) 
						{
								return Redirect::to('context_create')->with('error', 'The context is already in use. Please choose a different context.');
						}


						// Upload the MP3 file
						$originalFilename = $request->file('upload_file')->getClientOriginalName();
						$newFilename = "{$userId}_{$curdtm}_" . str_replace(' ', '_', $originalFilename); // Replace spaces with underscores

						// Store the file with the new filename
						$user_prompt = $request->file('upload_file')->storeAs('user_prompt_files', $newFilename);
		
						// Construct the full path to the user_prompt file
						$baseDirectory = storage_path('app');
						$fullUserPromptPath = "$baseDirectory/$user_prompt";

	        
						// Convert the uploaded MP3 file to WAV using FFmpeg
						$destinationPath = storage_path('app/convert_prompt_files');
						$filenameWithoutExtension = pathinfo($originalFilename, PATHINFO_FILENAME);
						$storedFilename = $filenameWithoutExtension . '.wav';

						$storedFilenameInDB = str_replace(' ', '_', $storedFilename);

						$fullConvertedWavPath = "$destinationPath/" . str_replace(' ', '_', $storedFilename); // Replace spaces with underscores


						// Convert the audio file using FFmpeg
        		$ffmpegCommand = "ffmpeg -i \"$fullUserPromptPath\" -ar 8000 -ac 1 -acodec pcm_s16le $fullConvertedWavPath";
        		exec($ffmpegCommand);
	

						$prompt = new PromptMaster([
							'user_id' => $userId,
							'ivr_id' => $IVR_id,
							'company_name' => $company_name,
							'states_id' => $location->id,
							'language_id' => $language->language_id,
							'type' => $type,
							'prompt_path' => $storedFilenameInDB,
							'context' => $context,
							'remarks' => $remarks,
							'prompt_status' => 'C',
							'prompt_entry_time' => now(),
						]);

						$prompt->save();

						$msg = 'Prompt - <span style="color: red;">"' . $request->context_value . '"</span> Created Successfully!!';
	
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



}	


