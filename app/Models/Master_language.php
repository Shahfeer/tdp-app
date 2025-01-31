<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master_language extends Model
{
    protected $table = 'master_languages'; // Replace with your actual table name

    protected $fillable = [
	'language_id',
        'language_name',
        'language_code',
        'language_status',
        'language_entdate',
    ];

    //protected $primaryKey = 'language_id';
}

