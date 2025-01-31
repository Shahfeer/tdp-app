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

				// $tableName = 'mobile_numbers_' . $userId;

				// // Check if the table exists and drop it
				// if (Schema::hasTable($tableName)) 
				// {
				// 	Schema::dropIfExists($tableName);
				// }


				// $user = Auth::user();
				// $available_credits = null;
				// if ($user->user_master_id == 2) {
				// 	$available_credits = $user->credits->available_credits;
				// }


				if ($request->ajax() && $request->has('upload_file')) 
				{

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
					//$newFilename = "/var/www/html/obd_call/storage/app/OBD_call_uploaded_files/{$filename}";

					$newFilename = $customFilePath . $filename;


					if ($file_type == 'xlsx' || $file_type == 'xlx') 
					{
							$command = 'sudo /usr/bin/unoconv -f csv ' . $newFilename . ' 2>&1';
							$output = shell_exec($command);

							$filename = str_replace(".xlsx", ".csv", $newFilename);
							$file = fopen($filename, 'r');
							if ($file) 
							{
									while (($row = fgetcsv($file)) !== false) 
									{
										if (count($row) == 1) {
											$res[] = [$row[0]];
									} else {
											// Assuming the row contains three values (three columns: number, name, audio_url)
											$res[] = [$row[0], $row[1]];
									}
									}
									fclose($file);
							} 
							else 
							{
									echo "Error opening the file.";
							}
					} 
					else if ($file_type == 'csv') 
					{
							$file = fopen($newFilename, 'r');
							if ($file) 
							{
									while (($row = fgetcsv($file)) !== false) 
									{
										if (count($row) == 1) {
											$res[] = [$row[0]];
									} else {
											// Assuming the row contains three values (three columns: number, name, audio_url)
											$res[] = [$row[0], $row[1]];
									}
									}
									fclose($file);
							} 
							else 
							{
									echo "Error opening the file.";
							}
					}
					else 
					{
						
							$error = "Please upload a valid Excel, CSV, or Text file!";
							return response()->json(['error' => $error]);
					}
					

					return response()->json([
							//'csv_file' => json_encode($res),
							'csv_file' => ($res),
					]);

				}
		}
}


