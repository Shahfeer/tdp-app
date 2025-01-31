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

class MessageController extends Controller
{


public function processMobileNumbers(Request $request)
{
	ini_set('memory_limit', '-1');

	ini_set('max_execution_time', 0); //0=NOLIMIT

	// Truncate the table
    	//MobileNumber::truncate();

	$userId = Session::get('user_id');

	$tableName = 'mobile_numbers_' . $userId;

	// Check if the table exists and drop it
    if (Schema::hasTable($tableName)) {
        //Schema::dropIfExists($tableName);
        DB::table($tableName)->truncate();
    }


	$user = Auth::user();
        $available_credits = null;
        if ($user->user_master_id == 2)
        {
                $available_credits = $user->credits->available_credits;
        }


    if ($request->ajax() && $request->has('validateMobno')) 
    {

	$mobileNumbers = $request->input('mobno');
	
        
        /* foreach ($newline as $line) 
	{
            $expl = explode(",", $line);
            
            foreach ($expl as $index => $number) 
	    {
               if ($index === 0) 
		{
		
                    continue;
                }
                $mobileNumbers[] = $number;
            }
        }*/

	//array_shift($mobileNumbers);

	
        
        // Store mobile numbers in the database
        $this->storeMobileNumbers($mobileNumbers, $userId, $tableName);
        
        
	// Check validation of numbers from the database
        $invalidCount = $this->validatePhoneNumbers($tableName);
	

	$result = $this->countDuplicates($tableName);

	$uniqueCount = $result['uniqueCount'];
	$duplicateCount = $result['duplicateCount'];

	$validCount = $uniqueCount - $invalidCount;
                
        // Return the results
        return response()->json([
	    'validCount' => $validCount,
            'duplicateCount' => $duplicateCount,
            'invalidCount' => $invalidCount,
            'totalCount' => count($mobileNumbers),
	    'available_credits' => $available_credits,
        ]);
    }
}


private function countDuplicates($tableName)
{

    $totalCount = DB::table($tableName)->count();
    $uniqueCount = DB::table($tableName)->distinct('number')->count();
    
    $duplicateCount = $totalCount - $uniqueCount;
    

	return [
        'uniqueCount' => $uniqueCount,
        'duplicateCount' => $duplicateCount,
    ];
}


private function validatePhoneNumbers($tableName)
{
	$invalidCount = DB::table($tableName)
		->select('number')
            	->whereRaw('LENGTH(number) != 10')
                ->orWhere('number', 'LIKE', '0%')
                ->orWhere('number', 'LIKE', '1%')
                ->orWhere('number', 'LIKE', '2%')
                ->orWhere('number', 'LIKE', '3%')
                ->orWhere('number', 'LIKE', '4%')
                ->orWhere('number', 'LIKE', '5%')
	->distinct('number')
        ->count();

	return $invalidCount;
}


private function storeMobileNumbers($mobileNumbers, $userId, $tableName)
{
    // Increase the chunk size for batch insert
    $chunkSize = 10000; // Adjust as needed


    $totalRecords = count($mobileNumbers);

   // Check if the table exists, and create it if not
    if (!Schema::hasTable($tableName)) {
        Schema::create($tableName, function ($table) {
            $table->id();
            $table->string('number');
        });
    }

    for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
        $chunk = array_slice($mobileNumbers, $i, $chunkSize);

        $mobileNumbersToInsert = [];
        foreach ($chunk as $number) {
            $number = trim($number);
            if (!empty($number)) {
                $mobileNumbersToInsert[] = ['number' => $number];
            }
        }

        if (!empty($mobileNumbersToInsert)) {
		DB::table($tableName)->insert($mobileNumbersToInsert);
        }

        unset($mobileNumbersToInsert);
    }

}



}


