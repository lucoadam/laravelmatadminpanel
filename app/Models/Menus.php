<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = "menus";
    //
    protected $fillable =[
		'name',
		'icon',
		'url_type',
		'url',
		'parent_id',
		'backend',
		'open_in_new_tab',
    ];
}
