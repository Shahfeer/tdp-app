<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// This code defines the Call model class, specifying its attributes that are fillable in the database.

class Call extends Model
{
    use HasFactory;
    protected $fillable = [
	'campaignId',
	'userId',
	'ivr_id',
	'campaign_name',
	'mobile',
	'no_of_mobile_numbers',
	'context',
  'caller_id',
	'retry_count',
	'remarks',
	'call_status',
	'call_entry_time',
	'cdrs_report',

    ];
}
