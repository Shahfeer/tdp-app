<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master_state extends Model
{
    protected $table = 'master_states'; // Replace with your actual table name

    protected $fillable = [
        'id',
        'name',
        'state_short_name',
        'country_id',
    ];

   // protected $primaryKey = 'id';
}

