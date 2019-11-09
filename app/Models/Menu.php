<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menus";
    //
    protected $fillable =[
		'name',
		'icon',
		'url_type',
		'url',
		'open_in_new_tab',
    ];
}