<?php

namespace App\Imports;
use App\Models\Call;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;


class UsersImport implements ToModel
{

    protected  $userId, $campaignId, $mobile_numbers, $context, $caller_id, $campaign;


     // Constructor to initialize properties
    public function __construct($userId, $campaignId, $mobile_numbers, $context, $caller_id, $campaign)
    {
	$this->userId=$userId;
	$this->campaignId=$campaignId;
	$this->mobile_numbers=$mobile_numbers;
        $this->context=$context;
        $this->caller_id=$caller_id;
        $this->campaign=$campaign;
    }


    // This method is called for each row in the Excel file
    public function model(array $row)
    {
      
        // Create a new Call model instance with data
        return new Call([

	'userId' => $this->userId,
	'campaignId' => $this->campaignId,
	'mobile' => $mobile_numbers,
        'context' => $this->context,
        'caller_id' => $this->caller_id,
        'campaign' => $this->campaign
    ]);

    // Save the new Call model instance to the database
   // $call->save();
    
}

}


