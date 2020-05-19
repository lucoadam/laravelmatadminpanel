<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Setting;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard=null)
    {
        $url = explode('://',url('/'))[1];
        $database = Client::where('url',$url);
        if($database->exists()) {
            return redirect('/admin/home');
        }

        return $next($request);
    }
}
