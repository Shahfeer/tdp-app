<?php
namespace App\Http\Controllers;
//use App\Events\WebSocketDataEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use App\Models\Call;
use App\Models\User;
use App\Models\cdr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\resources\views\createcampaignblade;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\Environment\Console;
use App\Http\Middleware\CheckAuthentication;
set_time_limit(0);
use DB;
use App\Models\PromptMaster;
use Illuminate\Support\Facades\URL; // Import the URL facade
use DateTime;

class CreatecampaignController extends Controller
{

  
  // Define the create campaign page
  public function fileImportExport()
    {
       return view('file-import');
    }
      

// Function to send a message and create a campaign

    public function sendMessage(Request $request) 
    {
	    // Log the start of the function execution
        Log::channel('custom_log')->info('create campaign_list function started.');


	    // Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);	

	    //session ID
        $userId = Session::get('user_id');
	    $userName = auth()->user()->name;

        Log::channel('custom_log')->info('User:' . auth()->user()->name);
	
	    $randomNumbers = '';
	    for ($i = 0; $i < 3; $i++) 
	    {
    		$randomNumbers .= rand(0, 9); // Generate a random number between 0 and 9
	    }


        //Get the variable from blade to import the date picker value into database
        $curdtm = date("YmdHis");
        $context = $request->get('context');

        // echo ''.$context.''; exit;

        $ivrId = DB::table('prompt_masters')
        ->select('ivr_id')
        ->where('context', '=', $context)
        ->first();

        $ivr_id = $ivrId->ivr_id;

        // echo ''.$ivr_id.''; exit;

        //dd($ivr_id);
        $caller_id = Config::get('app.caller_id');
        $campaignId = $context . "_" . $curdtm . $randomNumbers;   
	    $retry_count = $request->get('retry_count');

        // echo ''.$campaignId.''; exit;

        Log::channel('custom_log')->info('User Id: ' . $userId . ',  Campaign Name: ' . $campaignId. 'Context: '. $context);

		// Execute the stored procedure
		$query = DB::select('CALL create_campaign(?, ?, ?, ?, ?, ?)', 
		[
				$userId,
                $ivr_id,
                $campaignId,
                $context,
                $caller_id,
                $retry_count,

		]);

        //dd($query);

		// Check if the procedure returned a message
		if ($query && isset($query[0]->response_msg)) 
		{
	
		    	// Check the message for success or failure
				if ($query[0]->response_msg === 'success') 
				{
						Log::channel('custom_log')->info('create campaign query response: ' . $query[0]->response_msg);

						// Success message
						$msg = 'Thank You. Your campaign - <span style="color: red;">"' . $request->context."_".$curdtm . $randomNumbers . '"</span> has been created successfully!!';

                        return Redirect::to('createcampaign')->with('success', $msg);
				} 
				else 
				{
						Log::channel('custom_log')->info('create campaign query response: ' . $query[0]->response_msg);

						// Failure message
						$msg = 'Campaign Creation Failed';
						return Redirect::to('createcampaign')->with('error', $msg);
				}
		} 
		else 
		{
				Log::channel('custom_log')->info('create campaign query response: ' . json_encode($query));
				// Handle unexpected result or no message returned
				$msg = 'No Valid Numbers in this File';
				return Redirect::to('createcampaign')->with('error', $msg);
		}

    }


    public function get_context()
    {
        $userId = Session::get('user_id');
        $contexts = DB::table('prompt_masters')
        ->where('user_id', $userId)
        ->where('prompt_status', 'Y')
        ->select('context')
        ->distinct()
        ->orderBy('context')
        ->get();

        return response()->json($contexts);
    }


    public function get_audio_by_context(Request $request)
        {
            // Get the selected context from the AJAX request
            $context = $request->input('context');

             $customAudioUrl = config('app.prompt_url');

            // Query the database to retrieve the audio URL based on the context
            $audio = PromptMaster::where('context', $context)->first(); // Adjust the model and column name as needed

        if ($audio) {
            $prompt_path = $audio->prompt_path;
                // Construct the full audio URL by appending the baseUrl to audio_url
            //    $audioUrl = url("/storage/app/user_prompt_files/$prompt_path");
            $audioUrl = $customAudioUrl . $prompt_path;

                // Return the audio URL in JSON format
                return response()->json(['audio_url' => $audioUrl]);
            }

            // If the context is not found, return an error or an empty response
            return response()->json(['audio_url' => '']);
        }

        // Function to export data to an Excel file
        public function fileExport() 
        {
            return Excel::download(new UsersExport, 'users-collection.xlsx');
        }   


        // Function to display the create campaign view
        public function createCampaign() 
        { 

         // Apply the CheckAuthentication middleware to this method
            $this->middleware(CheckAuthentication::class);	

            return view ('createcampaign');
        }


        // Function to cancel a campaign
        public function cancel()
        {   
            $command="pkill -9 Copy";    
            shell_exec($command);
            
            return view('createcampaign');
        }
     
}

