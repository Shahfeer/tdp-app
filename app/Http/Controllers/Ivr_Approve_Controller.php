<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\CheckAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class Ivr_Approve_Controller extends Controller
{
    public function IvrApprove(Request $request)
    {
        Log::channel('custom_log')->info('Ivr Approve function function started.');

        // Apply the CheckAuthentication middleware to this method
        $this->middleware(CheckAuthentication::class);

        // Determine the user's role
        $userRole = Auth::user()->user_master_id;
        $userId = Auth::id();

        // Build the query based on the user's role
        $query = DB::table('prompt_masters');

        Log::channel('custom_log')->info('User:' . auth()->user()->name);

        Log::channel('custom_log')->info('User ID: ' . $userId);

        $query = DB::select(
            'CALL ivr_approve(?)',
            [
                $userId,
            ]
        );

        if ($request->ajax()) {

            // Create a DataTables response for the query results
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($query) {
                    $playBtn = '<a href="javascript:void(0)"  class="play-pause custom-btn" title="Play/Stop the Audio" style="width: 40px; height: 40px; color: black !important;" id="audioid_' . $query->prompt_id . '" onclick="playAudio(\'' . $query->prompt . '\', \'' . $query->prompt_id . '\')"><i class="fas fa-play"></i></a>';

                    $downloadBtn = '<a title="Download the Audio" href="storage/app/user_prompt_files/' . $query->prompt . '" class="custom-btn-one" style="width: 40px; height: 40px; color: #000;" download><svg viewBox="0 0 20 20" width="32" height="24" fill="#000000" xmlns="http://www.w3.org/2000/svg"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg></a>';

                    $actionButtons = '<div class="btn-group" style="gap:10px;">' . $playBtn . $downloadBtn . '</div>';
                    return $actionButtons;
                })
                ->addColumn('approve', function ($query) {

                    $actionButtons = '';

                    $approveBtn = '<a href="javascript:void(0)" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-300 hover:border-gray-100 rounded-full" onclick="select_sender(\'' . $query->prompt_id . '\',)">Approve</a>';

                    $declineBtn = '<a href="javascript:void(0)" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-400 hover:border-gray-100 rounded-full" onclick="decline_ivr(\'' . $query->prompt_id . '\')">Decline</a>';

                    $actionButtons = '<div class="btn-group">' . $approveBtn . '&nbsp;' . $declineBtn . '</div>';

                    return $actionButtons;

                })
                ->rawColumns(array("action", "approve"))
                ->make(true);
        }
        // Render the view for the campaign list
        return view('ivr_approve');
    }

    public function ApproveIVR(Request $request)
    {

        $prompt_id = $request->get('prompt_id');

        Log::channel('custom_log')->info('Prompt ID: ' . $prompt_id);

        if ($prompt_id != '') {

            $response = DB::table('prompt_masters')
                ->where('prompt_id', $prompt_id)
                ->update(['prompt_status' => 'Y']);

            Log::channel('custom_log')->info('Approved Data Response : ' . $response);

            return response()->json(['message' => 'IVR Approved Successfully']);
        }
        return response()->json(['message' => 'IVR Approved Failed']);
    }

    public function DeclineIVR(Request $request)
    {

        $prompt_id = $request->get('prompt_id');

        $remarks = $request->get('remarks');

        Log::channel('custom_log')->info('Prompt ID: ' . $prompt_id . ' and Comments: ' . $remarks);

        if ($prompt_id != '') {

            $response = DB::table('prompt_masters')
                ->where('prompt_id', $prompt_id)
                ->update(['prompt_status' => 'R', 'remarks' => $remarks]);

            Log::channel('custom_log')->info('Decline Data Response : ' . $response);

            return response()->json(['message' => 'IVR Declined Successfully']);
        }
        return response()->json(['message' => 'IVR Declined Failed']);
    }

}
