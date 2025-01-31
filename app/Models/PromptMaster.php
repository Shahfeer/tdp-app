<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptMaster extends Model
{
	protected $table = 'prompt_masters'; // Replace with your actual table name

	protected $fillable = [
		'prompt_id',
		'user_id',
		'ivr_id',
		'company_name',
		'campaign_type',
		'states_id',
		'language_id',
		'type',
		'prompt_path',
		'context',
		'remarks',
		'prompt_status',
		'prompt_entry_time',
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	protected $primaryKey = 'prompt_id';

}
