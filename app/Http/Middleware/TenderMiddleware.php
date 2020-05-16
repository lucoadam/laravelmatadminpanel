<?php

namespace App\Http\Middleware;

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
//        dd(DB::getDefaultConnection());
//        DB::setDefaultConnection('mysql');
//        if(Setting::where('key','database')->exists()) {
//            $setting = Setting::where('key', 'database')->first();
//            if (DB::getDefaultConnection() != $setting->value) {
//                DB::setDefaultConnection($setting->value);
//            }
//        }
        return $next($request);
    }
}
