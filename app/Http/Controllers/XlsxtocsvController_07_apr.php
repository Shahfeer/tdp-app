<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\CheckAuthentication;
use App\Models\User;
use App\Models\MobileNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class XlsxtocsvController extends Controller
{


	public function processXlsxToCsv(Request $request)
	{
		ini_set('memory_limit', '-1');

		ini_set('max_execution_time', 0); //0=NOLIMIT

		$userId = Session::get('user_id');

		$tableName = 'mobile_numbers_' . $userId;

		// Check if the table exists and drop it
		if (Schema::hasTable($tableName)) {
			Schema::dropIfExists($tableName);
		}


		$user = Auth::user();
		$available_credits = null;
		if ($user->user_master_id == 2) {
			$available_credits = $user->credits->available_credits;
		}


		if ($request->ajax() && $request->has('upload_file')) {

			//$file_data = $request->input('file_data');
			$file_type = $request->input('file_type');

			$res = array();
			$file_data = $request->file('upload_file');

			$userId = auth()->user()->id;
			$curdtm = date("Y-m-d H:i:s");

			$customFilePath = config('app.custom_file_path');
			$originalFilename = $file_data->getClientOriginalName();
			$filenameWithoutSpaces = str_replace(' ', '', $originalFilename);

			$filename = "{$curdtm}_obd_call_{$userId}_{$filenameWithoutSpaces}";
			$file_data->storeAs('OBD_call_uploaded_files', $filename);
			//$newFilename = "/opt/lampp/htdocs/obd_call/storage/app/OBD_call_uploaded_files/{$filename}";

			$newFilename = $customFilePath . $filename;

			if ($file_type == 'xlsx' || $file_type == 'xlx') {
				$command = 'sudo /usr/bin/unoconv -f csv ' . $newFilename . ' 2>&1';
				$output = shell_exec($command);

				$filename = str_replace(".xlsx", ".csv", $newFilename);
				$file = fopen($filename, 'r');
				if ($file) {
					while (($row = fgetcsv($file)) !== false) {
						foreach ($row as $cell) {
							array_push($res, $cell);
						}
					}
					fclose($file);
				} else {
					echo "Error opening the file.";
				}
			} else if ($file_type == 'csv') {
				$file = fopen($newFilename, 'r');
				if ($file) {
					while (($row = fgetcsv($file)) !== false) {
						foreach ($row as $cell) {
							array_push($res, $cell);
						}
					}
					fclose($file);
				} else {
					echo "Error opening the file.";
				}
			}
			/*	 else if($file_type == 'txt'){
							$file = fopen($newFilename, "r");
							while (!feof($file)) {
										$line = fgets($file);
										//echo $line;
								//array_push($res,$line);
							}
							$res = explode(",",$line);
						}  */

			/*	else if($file_type == 'txt'){
													$txt_data = '';
													$file = fopen($newFilename, "r");
													while (!feof($file)) {
																	$line = fgets($file);
																	$txt_data = $txt_data."".$line;
													}
													$res = explode(",",$txt_data);
									}  */ else {
				//echo "wrong file";
				$error = "Please upload a valid Excel, CSV, or Text file!";
				return response()->json(['error' => $error]);
			}

			return response()->json([
					'csv_file' => $res,
				]);
		}
	}
}
