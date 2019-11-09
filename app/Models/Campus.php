<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table = "campuss";
    //
    protected $fillable =[
		'name',
		'no_of_students',
    ];
}