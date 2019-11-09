<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = "offices";
    //
    protected $fillable =[
		'name',
		'location',
		'staff_no',
    ];
}