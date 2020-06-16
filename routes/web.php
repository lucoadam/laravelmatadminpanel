<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\Client;
use App\Models\settings\Module;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/alish', function () {
    if(\App\Models\Role::count()===0){

        $role=\App\Models\Role::create([
            'name'=>'Administrator',
            'all'=>1,
            'status'=>1,
            'created_by'=>1,
            "created_at" =>now(),
            "updated_at" => now()
        ]);
        DB::table('role_user')->insert([
            'user_id'=>1,
            'role_id'=>1
        ]);

    }

    $mod =Module::latest()->first();
    dd($mod);

//    $url = explode('://',url('/'))[1];
//    $database = Client::where('url',$url);
//    dd($url,$database->get()->toArray(),Client::all()->toArray());
//    if($database->exists()&&!auth()->check()){
//        $database= $database->first()->database;
//    }else{
//        $database= null;
//    }
//    if(!is_null($database)){
//        DB::setDefaultConnection($database);
//    }
//    dd(DB::getDefaultConnection());
});
Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('createandgenerate',function(){
        if(\Illuminate\Support\Facades\DB::getDefaultConnection()=='mysql') {
            $latest =\App\Models\Client::latest()->first();
            $menus = \App\Models\Menu::select(['name','icon','url_type','url','parent_id','backend','open_in_new_tab','created_at','updated_at'])->get()->toArray();
            $permissions = \App\Models\Permission::select(['name','display_name','status','created_by','created_at','updated_at'])->get()->toArray();

//            $this->info($latest->database);

            try{
                DB::setDefaultConnection($latest->database);
                try{
//                    $this->info(DB::getDefaultConnection());
                    Artisan::call('migrate');
                }catch (\Exception $exception){
//                    $this->info($exception);
                }
                try {
                    if(DB::table('users')->count('id')===0) {
                        DB::table('users')->insert([
                            'name' => $latest->name,
                            'email' => $latest->email,
                            'password' => $latest->password,
                        ]);
                    }
                    if(\App\Models\Menu::count()===0) {
                        foreach ($menus as $menu) {
                            \App\Models\Menu::create($menu);
                        }
                    }

                    if(\App\Models\Role::count()===0){

                        $role=\App\Models\Role::create([
                            'name'=>'Administrator',
                            'all'=>1,
                            'status'=>1,
                            'created_by'=>1,
                            "created_at" =>now(),
                            "updated_at" => now()
                        ]);
                        DB::table('role_user')->insert([
                            'user_id'=>1,
                            'role_id'=>1
                        ]);

                    }

                    if(\App\Models\Permission::count()===0) {
                        foreach ($permissions as $permission) {
                            $permission['created_by']=1;
                            \App\Models\Permission::create($permission);
                        }
                    }

                } catch (\Illuminate\Database\QueryException $q) {
                    dd($q);
//                    $this->info($q);
                }
            }catch (\InvalidArgumentException $exception){

            }


            DB::setDefaultConnection('mysql');

        return  redirect()->route('client.index');
        }
        return view('errors.404');
    })->name('createandgenerate');

    Route::get('table-list', function () {
        return view('pages.table_list');
    })->name('table');

    Route::get('typography', function () {
        return view('pages.typography');
    })->name('typography');

    Route::get('icons', function () {
        return view('pages.icons');
    })->name('icons');

    Route::get('map', function () {
        return view('pages.map');
    })->name('map');

    Route::get('notifications', function () {
        return view('pages.notifications');
    })->name('notifications');

    Route::get('upgrade', function () {
        return view('pages.upgrade');
    })->name('upgrade');
});

Route::group(['middleware' => 'auth','prefix'=>'admin'], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::get('search/{table}','SearchController@search');
    Route::get('/home', 'HomeController@index')->name('home');
        $directory= __DIR__.'/Generator/admin';
        $files=scandir($directory);
        foreach($files as $file){
            $array=explode('.',$file);
            if(end($array)=='php'){
                require $directory.'/'.$file;
            }
        }

    Route::group(['prefix'=>'settings','as'=>'settings.','namespace'=>'settings'],function(){
        Route::resource('module','ModuleController');
    });
});
