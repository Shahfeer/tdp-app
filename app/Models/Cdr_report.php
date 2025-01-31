<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cdr_report extends Model
{
    use HasFactory;

    protected $fillable = [
	'user_id',
	'campaign_id',
	'context',
	'download_url',
	'report_status',
	'report_entry_time',

    ];

    public $timestamps = false;

}
