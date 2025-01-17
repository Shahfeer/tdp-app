<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summary_report extends Model
{
    protected $table = 'summary_reports'; // Replace with your actual table name

    protected $fillable = [
        'user_id',
				'campaign_date',
				'campaign_id',
				'total_dialled',
				'total_success',
				'total_failed',
        'total_busy',
        'total_no_answer',
				'first_attempt',
        'retry_1',
        'retry_2',
				'success_percentage',
				'summary_report_status',
				'summary_report_entdate',
    ];

	public function user()
	{
    		return $this->belongsTo(User::class, 'user_id');
	}

	protected $primaryKey = 'summary_report_id';

	public $timestamps = false;

}	


