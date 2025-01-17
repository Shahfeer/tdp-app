<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call_holding_report extends Model
{
    protected $table = 'call_holding_reports'; // Replace with your actual table name

    protected $fillable = [
        'user_id',
				'campaign_id',
				'campaign_date',
				'1_5_secs',
				'6_10_secs',
				'11_15_secs',
				'16_20_secs',
				'21_25_secs',
				'26_30_secs',
				'31_45_secs',
				'46_60_secs',
				'total_calls',
				'call_answered',
				'call_not_answered',
				'call_holding_reprtstat',
				'call_holding_reprtdt',
    ];

	public function user()
	{
    		return $this->belongsTo(User::class, 'user_id');
	}

	protected $primaryKey = 'call_holding_reprtid';

	public $timestamps = false;

}	


