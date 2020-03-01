<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    protected $table = "memorys";
    //
    protected $fillable =[
		'name',
		'description',
    ];
}