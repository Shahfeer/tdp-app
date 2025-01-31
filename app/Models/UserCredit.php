<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
	protected $primaryKey = 'user_credits_id';

	protected $fillable = [
    'user_credits_id',
    'user_id',
    'total_credits',
    'used_credits',
    'available_credits',
    'expiry_date',
    'uc_status',
    'uc_entry_date',
];
public $incrementing = false;
}
