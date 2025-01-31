<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neron_master extends Model
{
    protected $table = 'neron_masters'; // Replace with your actual table name
    protected $primaryKey = 'server_id';
    public $timestamps = false;

    protected $fillable = [
				'neron_id',
        'board_name',
        'server_id',
        'neron_client_id',
        'ip_address',
        'neron_status',
        'running_status',
				'neron_con_time',
    ];

    //protected $primaryKey = 'language_id';
}

