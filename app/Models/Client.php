<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $connection = 'mysql';
    protected $table = "clients";
    //
    protected $fillable =[
		'name',
		'email',
		'url',
		'database',
        'password'
    ];
}
