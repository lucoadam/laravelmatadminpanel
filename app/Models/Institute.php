<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    protected $table="institutes";
    //
    protected $fillable =[
		'name',
		'abbr',
		'full_abbr',
    ];
}