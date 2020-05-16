<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = "contents";
    //
    protected $fillable =[
		'title',
		'image_id',
		'file_id',
		'description',
		'type',
		'posted_by',
    ];
			public function file(){
        return $this->belongsTo(File::class,'file_id');
    }
}