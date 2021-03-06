<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Setting;
use App\User;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = explode('://',url('/'))[1];
        $database = Client::where('url',$url);
        if($database->exists()&&!auth()->check()){
            $database= $database->first()->database;
        }else{
            $database= null;
        }
        if(!is_null($database)){
            DB::setDefaultConnection($database);
        }
        return $next($request);
    }
}
