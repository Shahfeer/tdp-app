<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class cdr extends Model
{
    protected $fillable = [
	'campaignId',
	'accountcode',
        'src',
        'dst',
	'clid',
	'channel',
        'calldate',
        'answerdate',
	'last_call_time',
        'hangupdate',
	'billsec',
        'disposition',
	'retry_count',
        'amaflags',
        'recordurl',
        'direction',
	'entry_date',
	'cdrs_status',
	'report_status',

    ];
    
    //use HasFactory;
    
}
