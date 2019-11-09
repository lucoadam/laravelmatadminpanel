<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $table = "librarys";
    //
    protected $fillable =[
		'name',
		'content',
    ];
}