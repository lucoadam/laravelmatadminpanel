<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Http\Requests\client\ClientCreateRequest;
use App\Http\Requests\client\ClientEditRequest;
use App\Http\Requests\client\ClientStoreRequest;
use App\Http\Requests\client\ClientUpdateRequest;
use App\Http\Requests\client\ClientDeleteRequest;
use App\Http\Requests\client\ClientViewRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('showClient');
    }

    /**
     * Display a listing of the client
     *
     * @param  \App\Client  $model
     * @return \Illuminate\View\View
     */
    public function index(ClientViewRequest $request,Client $model)
    {
        $mod=$model->all();
        $count=count($mod)+1;
        foreach($mod as $k=>$m){
            $m->index=$count-1;
            $count--;
        }

        return view('client.index', ['client' => $mod]);
    }

    /**
     * Show the form for creating a new client
     *
     * @return \Illuminate\View\View
     */
    public function create(ClientCreateRequest $request)
    {

        return view('client.create');
    }

    /**
     * Store a newly created client in storage
     *
     * @param  \App\Http\Requests\ClientRequest  $request
     * @param  \App\Client  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ClientStoreRequest $request, Client $model)
    {
        $input= $request->except('_token');
        $input['password']=Hash::make('admin');
        $input['database']=strtolower(implode('_',explode(' ',$input['database'])));
        $fileContent = require(base_path('config/database.php'));
        $newConnection =$fileContent['connections']['mysql'];
        $newConnection['database']=$input['database'];
        $fileContent['connections'][$input['database']]=$newConnection;


        try {
            if($d= $model->create($input)){
                $done= DB::statement('create database if not exists '.$input['database']);
                $this->writeContentToDatabaseFile($fileContent);
            }
        }catch (QueryException $q){
//            dd($q);
        }
//        dd($done);
        if(DB::getDefaultConnection()!='mysql'){
            DB::setDefaultConnection('mysql');
        }


        return redirect()->route('client.index')->withStatus(__('Client successfully created.'));
    }

    /**
     * Show the form for editing the specified client
     *
     * @param  \App\Client  $client
     * @return \Illuminate\View\View
     */
    public function edit(Client $client)
    {
        return view('client.edit', compact('client'));
    }

    /**
     * Update the specified client in storage
     *
     * @param  \App\Http\Requests\ClientRequest  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ClientUpdateRequest $request,Client  $client)
    {
        $input =$request->all();


        $client->update($input);
        return redirect()->route('client.index')->withStatus(__('Client successfully updated.'));
    }

    /**
     * Remove the specified client from storage
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ClientDeleteRequest $request,Client  $client)
    {
        $msg='';
        if($client->exists()) {
            DB::statement('drop database if exists ' . $client->database);
            $fileContent = require(base_path('config/database.php'));
//        $fileContent['connections'];
            unset($fileContent['connections'][$client->database]);
            $this->writeContentToDatabaseFile($fileContent);
            $client->delete();
            $msg='Client successfully deleted.';
        }else{
            $msg= 'Client doesn\'t exists.';
        }

        return redirect()->route('client.index')->withStatus(__($msg));
    }

    private function writeContentToDatabaseFile($content){
        $eachDatabase=[
            'sqlite'=>"\t\t'sqlite' => [\n\t\t\t'driver' => 'sqlite',\n\t\t\t'url' => env('DATABASE_URL'),\n\t\t\t'database' => env('DB_DATABASE', database_path('database.sqlite')),\n\t\t\t'prefix' => '',\n\t\t\t'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),\n\t\t ],\n",
            'mysql'=>"\t\t'mysql' => [\n\t\t\t'driver' => 'mysql',\n\t\t\t'url' => env('DATABASE_URL'),\n\t\t\t'host' => env('DB_HOST', '127.0.0.1'),\n\t\t\t'port' => env('DB_PORT', '3306'),\n\t\t\t'database' => env('DB_DATABASE', 'forge'),\n\t\t\t'username' => env('DB_USERNAME', 'forge'),\n\t\t\t'password' => env('DB_PASSWORD', ''),\n\t\t\t'unix_socket' => env('DB_SOCKET', ''),\n\t\t\t'charset' => 'utf8mb4',\n\t\t\t'collation' => 'utf8mb4_unicode_ci',\n\t\t\t'prefix' => '',\n\t\t\t'prefix_indexes' => true,\n\t\t\t'strict' => true,\n\t\t\t'engine' => null,\n\t\t\t'options' => extension_loaded('pdo_mysql') ? array_filter([\n\t\t\t\tPDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),\n\t\t\t]) : [],\n\t\t],\n",
            'pgsql'=>"\t\t'pgsql' => [\n\t\t\t'driver' => 'pgsql',\n\t\t\t'url' => env('DATABASE_URL'),\n\t\t\t'host' => env('DB_HOST', '127.0.0.1'),\n\t\t\t'port' => env('DB_PORT', '5432'),\n\t\t\t'database' => env('DB_DATABASE', 'forge'),\n\t\t\t'username' => env('DB_USERNAME', 'forge'),\n\t\t\t'password' => env('DB_PASSWORD', ''),\n\t\t\t'charset' => 'utf8',\n\t\t\t'prefix' => '',\n\t\t\t'prefix_indexes' => true,\n\t\t\t'schema' => 'public',\n\t\t\t'sslmode' => 'prefer',\n\t\t],\n",
            'sqlsrv'=>"\t\t'sqlsrv' => [\n\t\t\t'driver' => 'sqlsrv',\n\t\t\t'url' => env('DATABASE_URL'),\n\t\t\t'host' => env('DB_HOST', 'localhost'),\n\t\t\t'port' => env('DB_PORT', '1433'),\n\t\t\t'database' => env('DB_DATABASE', 'forge'),\n\t\t\t'username' => env('DB_USERNAME', 'forge'),\n\t\t\t'password' => env('DB_PASSWORD', ''),\n\t\t\t'charset' => 'utf8',\n\t\t\t'prefix' => '',\n\t\t\t'prefix_indexes' => true,\n\t\t],\n"
        ];
        $fileContents = "<?php\nuse Illuminate\Support\Str;\nreturn [\n";
        foreach($content as $k=>$val){
            if($k=='default'){
                $fileContents.="\t'default' => env('DB_CONNECTION', 'mysql'),\n";
            }elseif($k=='migrations'){
                $fileContents.="\t'migrations' =>'migrations',\n";
            }elseif($k=='redis'){
                $fileContents.="\t'redis' => [\n\t\t'client' => env('REDIS_CLIENT', 'phpredis'),\n\t\t'options' => [\n\t\t\t'cluster' => env('REDIS_CLUSTER', 'redis'),\n\t\t\t'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),\n\t\t],\n\t\t'default' => [\n\t\t\t'url' => env('REDIS_URL'),\n\t\t\t'host' => env('REDIS_HOST', '127.0.0.1'),\n\t\t\t'password' => env('REDIS_PASSWORD', null),\n\t\t\t'port' => env('REDIS_PORT', '6379'),\n\t\t\t'database' => env('REDIS_DB', '0'),\n\t\t],\n\t\t'cache' => [\n\t\t\t'url' => env('REDIS_URL'),\n\t\t\t'host' => env('REDIS_HOST', '127.0.0.1'),\n\t\t\t'password' => env('REDIS_PASSWORD', null),\n\t\t\t'port' => env('REDIS_PORT', '6379'),\n\t\t\t'database' => env('REDIS_CACHE_DB', '1'),\n\t\t],\n\t],\n";
            }else {
                if($k=='connections'){
                    $fileContents.="\t'connections'=> [\n";
                    foreach ($val as $key=>$v){
                        if(array_key_exists($key,$eachDatabase)){
                            $fileContents.=$eachDatabase[$key];
                        }else{
                            $fileContents.="\t\t'".$key."' => [\n\t\t\t'driver' => 'mysql',\n\t\t\t'url' => env('DATABASE_URL'),\n\t\t\t'host' => env('DB_HOST', '127.0.0.1'),\n\t\t\t'port' => env('DB_PORT', '3306'),\n\t\t\t'database' => '".$key."',\n\t\t\t'username' => env('DB_USERNAME', 'forge'),\n\t\t\t'password' => env('DB_PASSWORD', ''),\n\t\t\t'unix_socket' => env('DB_SOCKET', ''),\n\t\t\t'charset' => 'utf8mb4',\n\t\t\t'collation' => 'utf8mb4_unicode_ci',\n\t\t\t'prefix' => '',\n\t\t\t'prefix_indexes' => true,\n\t\t\t'strict' => true,\n\t\t\t'engine' => null,\n\t\t\t'options' => extension_loaded('pdo_mysql') ? array_filter([\n\t\t\t\tPDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),\n\t\t\t]) : [],\n\t\t],\n";
                        }
                    }
                    $fileContents.="\t],\n";
                }
            }
        }
        $fileContents.="];";
        file_put_contents(base_path('config/database.php'),$fileContents);

    }
}
