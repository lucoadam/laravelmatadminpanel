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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/alish', function () {
    $parent= \App\Models\Menu::select(['id','name','url'])->orderBy('name')->where('parent_id',0)->get();
    foreach($parent as $p){
        $child =\App\Models\Menu::select(['id','name','url'])->orderBy('name')->where('parent_id',$p->id);
        if($child->exists()){
            $p['children']=$child->get();
        }

    }
    return $parent;
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
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

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::resource('students','StudentsController');
    Route::resource('institute','InstituteController');
    Route::get('search/{table}','SearchController@search');
    Route::group(['prefix'=>'admin'],function(){
        $directory= __DIR__.'/Generator';
        $files=scandir($directory);
        foreach($files as $file){
            $array=explode('.',$file);
            if(end($array)=='php'){
                require $directory.'/'.$file;
            }
        }
    });

	Route::group(['prefix'=>'settings','as'=>'settings.','namespace'=>'settings'],function(){
	   Route::resource('department','DepartmentController');
    });
});

