<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\CheckAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PromptMaster;
use DataTables;
use DB;

class Contextlist_Controller extends Controller
{
	public function context_list(Request $request)
	{
		Log::channel('custom_log')->info('campaign_list function started.');

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
			'CALL context_list(?)',
			[
				$userId,
			]
		);

		if ($request->ajax()) {

			// Create a DataTables response for the query results
			return Datatables::of($query)
				->addIndexColumn()
				->addColumn('action', function ($query) {
					if ($query->campaign_type == 'N') {
						$playBtn = '<a href="javascript:void(0)"  class="play-pause custom-btn" title="Play/Stop the Audio" style="width: 40px; height: 40px; color: #000;" id="audioid_' . $query->prompt_id . '" onclick="playAudio(\'' . $query->prompt . '\', \'' . $query->prompt_id . '\')"><i class="fas fa-play"></i></a>';

						$downloadBtn = '<a title="Download the Audio" href="storage/app/user_prompt_files/' . $query->prompt . '" style="width: 40px; height: 40px; color: #000;" class="custom-btn-one"  download><svg viewBox="0 0 20 20" width="32" height="24" fill="#000000" xmlns="http://www.w3.org/2000/svg"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg></a>';

						$actionButtons = '<div class="btn-group">' . $playBtn . $downloadBtn . '</div>';
						return $actionButtons;
					} else {
						return '-';
					}
				})
				->make(true);

		}
		// Render the view for the campaign list
		return view('context_list');
	}

}
