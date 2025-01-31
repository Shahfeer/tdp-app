<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckAuthentication;
use App\Models\Call;
use App\Models\Neron_master;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class GsmBoard_Controller extends Controller
{
    public function gsm_board(Request $request)
    {
        Log::channel('custom_log')->info('credit_management function started.');

        // Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);

        // Get the authenticated user's ID
        $userId = Auth::id();

        Log::channel('custom_log')->info('Logged in user: ' . auth()->user()->name);

        // Get the user master id to check if admin or user
        $userMasterId = Auth::user()->user_master_id;

        Log::channel('custom_log')->info('User master ID: ' . $userMasterId);

        $query = DB::select('CALL gsm_board()');

        if ($request->ajax()) 
        {
            
                // Create a DataTables response for the query results
                return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('running_status', function ($query) 
                    {
                        if ($query->running_status === 'O') 
                        {
                            return 'Idle';
                        } 
                        elseif ($query->running_status === 'P') 
                        {
                            // Get the campaign IDs for processing status
                            $campaignIds = $this->getCampaignId($query->neron_id);

                            if (!empty($campaignIds)) 
                            {
                                // Join multiple campaign IDs with comma and space
                                $campaignStr = implode(', ', $campaignIds);
                                return 'Campaign ' . '['  . $campaignStr . ']' . ' - Processing';
                            } 
                            else 
                            {
                                return 'No Processing Campaign';
                            }
                            
                        } 
                        elseif ($query->running_status === 'S') 
                        {
                            /// Get the campaign IDs for processing status
                            $campaignIds = $this->getstopCampaign($query->neron_id);

                            if (!empty($campaignIds)) 
                            {
                                // Join multiple campaign IDs with comma and space
                                $campaignStr = implode(', ', $campaignIds);
                                return 'Campaign ' . '['  . $campaignStr . ']' . ' - Stop';
                            } 
                            else 
                            {
                                return 'No Stop Campaign';
                            }
                        } 
                        else {
                            return 'Unknown';
                        }
                    })
                    ->addColumn('action', function($query) {
                        $btn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" style="background-color: rgba(55, 65, 81); color: white; line-height: 40px;" onclick="add_name(\'' . $query->server_id . '\')">Edit</a>';

                        // $btn = '<a href="javascript:void(0)" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" style="background-color: rgba(55, 65, 81); color: white; line-height: 40px;">Add</a>';
                        return $btn;
                    })                
                    ->make(true);
        }

        // Render the view for the campaign list
        return view('gsm_board');
    }

    protected function getCampaignId($neronId)
    {
        // Query to retrieve the campaign ID based on $neronId and running status
        // Replace this query with your actual logic to fetch the campaign ID
        $campaignId = DB::table('calls')
                        ->select('campaign_id')
                        ->where('neron_id', '=', $neronId)
                        ->where('call_status', 'P')
                        ->get();

        if ($campaignId->isNotEmpty()) 
        {
            return $campaignId->pluck('campaign_id')->toArray();
        } 
        else 
        {
            return []; // Return an empty array if no campaign IDs were found
        }
    }


    protected function getstopCampaign($neronId)
    {
        // Query to retrieve the campaign ID based on $neronId and running status
        // Replace this query with your actual logic to fetch the campaign ID
        $campaignId = DB::table('calls')
                        ->select('campaign_id')
                        ->where('neron_id', '=', $neronId)
                        ->where('call_status', 'S')
                        ->get();

        if ($campaignId->isNotEmpty()) 
        {
            return $campaignId->pluck('campaign_id')->toArray();
        } 
        else 
        {
            return []; // Return an empty array if no campaign IDs were found
        }
    }

    public function channel_status(Request $request) 
    {
        $serverId = $request->input('serverId');

        $neronId = Neron_master::where('server_id', $serverId)->value('neron_id');

        $channel_status = DB::table('channel_status')
        ->select('channel_id AS channel', 'sim_number', 'state AS status')
        ->where('server_id', $neronId) // Corrected the where clause to use $neronId
        ->get();

      // echo $channel_status;
        return response()->json(['channel_status' => $channel_status]);

    }


    public function add_board_name(Request $request)
    {
        // Get the user ID and credit amount from the request
        $serverId = $request->input('server_id');
       
        $addName = $request->input('add_name');
        
        // You can use Eloquent ORM to update the credits
        $boardName = Neron_master::where('server_id', $serverId)->first();

        if ($boardName) 
        {
            // Update the board_name attribute
            $boardName->board_name = $addName;
        
            // Save the changes
            $boardName->save();

            Log::info('name updated for server ID ' . $serverId);
        
            // Optionally, return a success response
            return redirect()->back()->with('success', 'Name updated successfully');
        } 
        else 
        {
            // Handle the case where the record with the provided server ID doesn't exist
            return redirect()->back()->with('success' , 'Record not found for the given server ID');
        }
        
    }


    public function campaign_details(Request $request)
    {
        $running_status = $request->input('campaign_id');
           
        // Extract campaign ID using regular expression
        preg_match('/\[(\d+)\]/', $running_status, $matches);
        $campaign_id = isset($matches[1]) ? $matches[1] : null;

        // Now $campaign_id contains the extracted campaign ID

       $campaign_details = DB::table('calls')
                        ->select('calls.*', 'users.name as user_name') // Select the fields you need
                        ->leftJoin('users', 'calls.userId', '=', 'users.id') // Join with the users table
                        ->where('calls.campaign_id', $campaign_id)
                        ->first();

        if ($campaign_details) 
        {
            return response()->json(['campaign_details' => $campaign_details], 200);
        } 
        else 
        {
            return response()->json(['message' => 'campaign details not found'], 404);
        }
    }


}

