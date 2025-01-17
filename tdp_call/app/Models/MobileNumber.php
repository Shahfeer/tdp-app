<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileNumber extends Model
{
    protected $table = 'mobile_numbers';
    protected $fillable = ['number'];


public $timestamps = false;
}

