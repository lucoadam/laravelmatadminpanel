<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\settings\DepartmentRequest;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\settings\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ModuleController extends Controller
{
    private $modelCamelCase;
    private $modelTableName;
    private $modelName;
    private $multiMigration = [];
    public function __construct()
    {
        $this->middleware('showClient');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Module $model)
    {
        //
        return view('settings.modules.index', ['modules' => $model->orderBy('id', 'desc')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('settings.modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request, Module $module)
    {
        if ($request->all()['name'] != 'module') {
            /**
             * Conversion of module name to camel case and table name
             */
            $this->modelCamelCase = $this->toCamelCase($request->all()['name']);
            $this->modelTableName = $this->toTableName($request->all()['name']);
            $modelName = ucwords(strtolower($request->all()['name']));
            $this->modelName = ucwords(strtolower($request->all()['name']));
            /**
             * Checking if model name is similar to the following role,permission,user and module
             * and redirecting such back to the create
             */
            if ($modelName == "Role" || $modelName == 'Permission' || $modelName == 'User' || $modelName == 'Module') {
                return redirect()->route('settings.module.create');
            }
            /**
             * Converting the json serialize fileds to php array
             */

            $fields = json_decode($request->all()['field']); //array('name'=>'string','country'=>'string','city'=>'text','salary'=>'integer');
            /**
             * Checking and deleting previous migration and database for the
             * module that was yet to be deleted
             */
            $mig = scandir(base_path() . '/database/migrations');
            $basePath = explode('public', public_path())[0];
            $mArray = preg_grep('/' . strtolower($this->modelTableName) . 's/', $mig);
            /**
             * Checking if the migration really exists
             */
            if (count($mArray) > 0) {
                foreach ($mArray as $migrationName) {
                    /**
                     * Checking and clearing each multi migration fields
                     */
                    if (strpos($migrationName, '_add_foreign_keys_to_')) {
                        $tableName = explode('_table.php', explode('_add_foreign_keys_to_', $migrationName)[1])[0];
                        Schema::dropIfExists($tableName);
                    }

                    $path = base_path() . '/database/migrations/' . $migrationName;
                    $migrationName = explode('.', $migrationName)[0];

                    if (File::exists($path)) {
                        File::delete($path);
                        DB::table('migrations')->where('migration', $migrationName)->delete();
                        Schema::dropIfExists(strtolower($this->modelTableName) . 's');
                    }
                }
            }
            /**
             * Only creating the migration content and migrating same migration if the database doesnot have
             * $models table
             */
            if (!Schema::hasTable(strtolower($this->modelTableName) . 's')) {
                $maxMigration = explode('_', DB::table('migrations')->max('migration'))[3] + 1;
                $migrationPath = $basePath . 'database/migrations/' . now()->format('Y_m_d') . '_' . $maxMigration . '_create_' . strtolower($this->modelTableName) . 's_table.php';
                $migrationContent = $this->migrationContent(strtolower($modelName), $fields);
                $migrationGenerate = File::put($migrationPath, $migrationContent);
                exec('cd ' . $basePath . ' && php artisan migrate');
                foreach ($this->multiMigration as $eachMigration) {
                    $maxMigration += 1;
                    $migrationPath = $basePath . 'database/migrations/' . now()->format('Y_m_d') . '_' . $maxMigration . '_add_foreign_keys_to_' . strtolower($this->modelTableName) . '_' . $eachMigration[2] . '_table.php';
                    $migrationContent = $this->multiRelationMigrationContent($eachMigration[0], $eachMigration[1], $eachMigration[2]);
                    $migrationGenerate = File::put($migrationPath, $migrationContent);
                    exec('cd ' . $basePath . ' && php artisan migrate');
                }
                $this->multiMigration = [];
            }
            /**
             * Making content and Generating Models for the $modelName module
             */
            $modelPath = $basePath . 'app/Models/' . $this->modelCamelCase . '.php';
            $modelContent = $this->modelContent($modelName, $fields);
            $modelGenerate = File::put($modelPath, $modelContent);
            /**
             * Making content and Generating Request for the $modelName module
             */
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase);
            //creating the directory of module name in the app/Http/Requests/ folder
            File::isDirectory($requestPath) or File::makeDirectory($requestPath, 0777, true, true);
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase) . '/' . $this->modelCamelCase . 'StoreRequest.php';
            $requestContent = $this->requestContent($modelName, $fields, 'Store');
            $requestGenerate = File::put($requestPath, $requestContent);
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase) . '/' . $this->modelCamelCase . 'UpdateRequest.php';
            $requestContent = $this->requestContent($modelName, $fields, 'Update');
            $requestGenerate = File::put($requestPath, $requestContent);
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase) . '/' . $this->modelCamelCase . 'EditRequest.php';
            $requestContent = $this->requestContent($modelName, $fields, 'Edit');
            $requestGenerate = File::put($requestPath, $requestContent);
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase) . '/' . $this->modelCamelCase . 'CreateRequest.php';
            $requestContent = $this->requestContent($modelName, $fields, 'Create');
            $requestGenerate = File::put($requestPath, $requestContent);
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase) . '/' . $this->modelCamelCase . 'DeleteRequest.php';
            $requestContent = $this->requestContent($modelName, $fields, 'Delete');
            $requestGenerate = File::put($requestPath, $requestContent);
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase) . '/' . $this->modelCamelCase . 'ViewRequest.php';
            $requestContent = $this->requestContent($modelName, $fields, 'View');
            $requestGenerate = File::put($requestPath, $requestContent);
            /**
             * Making content and Generating Controller for the $modelName module
             */
            $controllerPath = $basePath . '/app/Http/Controllers/' . $this->modelCamelCase . 'Controller.php';
            $controllerContent = $this->controllerContent($modelName, $fields);
            $contollergenerate = File::put($controllerPath, $controllerContent);
            /**
             * Making content and Generating repesctive views for the $modelName module
             */
            $viewPath = $basePath . 'resources/views/' . strtolower($this->modelCamelCase);
            File::isDirectory($viewPath) or File::makeDirectory($viewPath, 0777, true, true);
            $createContent = $this->createView($modelName, $fields);
            $createGenerate = File::put($viewPath . '/create.blade.php', $createContent);
            $editContent = $this->editView($modelName, $fields);
            $editGenerate = File::put($viewPath . '/edit.blade.php', $editContent);
            $indexContent = $this->viewIndex($modelName, $fields);
            $indexGenerate = File::put($viewPath . '/index.blade.php', $indexContent);
            $mediaRoute = property_exists($fields,'images')? "\n\t"
                .'Route::post(\''.strtolower($this->modelCamelCase).'/media\', \''.$this->modelCamelCase.'Controller@storeMedia\')->name(\''.strtolower($this->modelCamelCase).'.storeMedia\');'."\n\t"
                .'Route::post(\''.strtolower($this->modelCamelCase).'/media/delete\', \''.$this->modelCamelCase.'Controller@deleteMedia\')->name(\''.strtolower($this->modelCamelCase).'.deleteMedia\');'."\n\t"
                :'';
            $routesPath = $basePath . 'routes/Generator/admin';
            File::isDirectory($routesPath) or File::makeDirectory($routesPath, 0777, true, true);
            File::put($routesPath . '/' . $this->modelCamelCase . '.php', '<?php' . "\n\t" . 'Route::resource(\'' . strtolower($this->modelCamelCase) . '\',\'' . $this->modelCamelCase . 'Controller\');'.$mediaRoute);

            $module->create(['name' => $request->all()['name']]);

            if ($request->has('parent') && !is_null($request->get('parent'))) {
                $parent = Menu::firstOrCreate(['name' => $request->get('parent'), 'url' => '#']);
                Menu::firstOrCreate(['name' => $request->all()['name'], 'url' => strtolower($this->modelCamelCase) . '.index', 'parent_id' => $parent->id]);
            } else {
                Menu::firstOrCreate(['name' => $request->all()['name'], 'url' => strtolower($this->modelCamelCase) . '.index']);
            }
        }
        return redirect()->route('settings.module.index')->withStatus(__('Module successfully created.'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        return view('settings.modules.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        //
        $module->update($request->all());
        return redirect()->route('settings.module.index')->withStatus(__('Module successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        //
        $modelName = ucwords($module->name);
        $this->modelName = ucwords($module->name);
        $this->modelTableName = $this->toTableName($this->modelName);
        $this->modelCamelCase = $this->toCamelCase($this->modelName);


        $permissions = Permission::where('name', 'like', '%' . strtolower($this->modelCamelCase))->get();
        if ($modelName != 'Menus') {
            $mig = scandir(base_path() . '/database/migrations');
            // DB::table('migrations')->where('migration','2019_11_08_830097_create_librarys_table')->delete();
            $mArray = preg_grep('/' . strtolower($this->modelTableName) . '/', $mig);
            $basePath = explode('public', public_path())[0];
            $modelPath = $basePath . 'app/Models/' . $this->modelCamelCase . '.php';
            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase);
            $controllerPath = $basePath . '/app/Http/Controllers/' . $this->modelCamelCase . 'Controller.php';
            $viewPath = $basePath . 'resources/views/' . strtolower($this->modelCamelCase);
            $routesPath = $basePath . 'routes/Generator/admin';


            if (count($mArray) > 0) {
                foreach ($mArray as $migrationName) {
                    /**
                     * Checking and clearing each multi migration fields
                     */
                    if (strpos($migrationName, '_add_foreign_keys_to_')) {
                        $tableName = explode('_table.php', explode('_add_foreign_keys_to_', $migrationName)[1])[0];
                        Schema::dropIfExists($tableName);
                    }

                    $path = base_path() . '/database/migrations/' . $migrationName;
                    $migrationName = explode('.', $migrationName)[0];

                    if (File::exists($path)) {
                        File::delete($path);
                        DB::table('migrations')->where('migration', $migrationName)->delete();
                    }
                }
                Schema::dropIfExists(strtolower($this->modelTableName) . 's');
            }
            $files = [
                $modelPath,
                $requestPath,
                $controllerPath,
                $viewPath . '/create.blade.php',
                $viewPath . '/index.blade.php',
                $viewPath . '/view.blade.php',
                $viewPath . '/edit.blade.php',
                $routesPath . '/' . $this->modelCamelCase . '.php'
            ];
            foreach ($files as $file) {
                if (File::exists($file)) {
                    File::delete($file);
                }
            }
            if (File::isDirectory($requestPath)) {
                File::deleteDirectory($requestPath);
            }
            if (File::isDirectory($viewPath)) {
                File::deleteDirectory($viewPath);
            }
            $menu = Menu::where('url', strtolower($this->modelCamelCase) . '.index')->first();
            if (!is_null($menu)) {
                if ($menu->parent_id != 0 && Menu::where('parent_id', $menu->parent_id)->count() == 1) {
                    $pMenu = Menu::find($menu->parent_id);
                    if ($pMenu->exists()) {
                        $menu->delete();
                        $pMenu->delete();
                    }
                } else {
                    $menu->delete();
                }
            }
            foreach ($permissions as $permission) {
                $permission->delete();
            }

            $module->delete();
        } else {
            return redirect()->route('settings.module.index')->withErrors(__('Module not found!!'));
        }
        return redirect()->route('settings.module.index')->withStatus(__('Module successfully deleted.'));
    }

    private function multiRelationMigrationContent($modelName, $tableName, $relatedModule)
    {
        return "<?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;

        class AddForeignKeysTo" . $this->modelCamelCase . $this->toCamelCase($relatedModule) . "Table extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create('" . $tableName . "_" . $relatedModule . "', function (Blueprint $" . "table) {
                    $" . "table->increments('id');
                    $" . "table->bigInteger('" . $tableName . "_id')->unsigned()->index('" . $tableName . "_" . $relatedModule . "_" . $tableName . "_id_foreign');
                    $" . "table->bigInteger('" . $relatedModule . "_id')->unsigned()->index('" . $tableName . "_" . $relatedModule . "_" . $relatedModule . "_id_foreign');
                });
                Schema::table('" . $tableName . "_" . $relatedModule . "', function (Blueprint " . '$table' . ") {
                    " . '$table' . "->foreign('" . $tableName . "_id')->references('id')->on('" . $tableName . "s')->onUpdate('RESTRICT')->onDelete('CASCADE');
                    " . '$table' . "->foreign('" . $relatedModule . "_id')->references('id')->on('" . $relatedModule . "s')->onUpdate('RESTRICT')->onDelete('CASCADE');
                });
            }

            /**
             * Reverse the migrations.
             *
             * @return void
             */
            public function down()
            {
                Schema::table('" . $tableName . "_" . $relatedModule . "', function (Blueprint " . '$table' . ") {
                    " . '$table' . "->dropForeign('" . $tableName . "_" . $relatedModule . "_" . $relatedModule . "_id_foreign');
                    " . '$table' . "->dropForeign('" . $tableName . "_" . $relatedModule . "_" . $tableName . "_id_foreign');
                });
                Schema::drop('" . $tableName . "_" . $relatedModule . "');
            }
        }
        ";
    }


    private function migrationContent($table, $fields = ['name' => 'string'])
    {
        $fieldContent = '';
        foreach ($fields as $key => $field) {
            $columnName = explode('__', $key);
            $reltable = $columnName[0] . 's';
            if (Schema::hasTable($reltable)) {

                if (strpos($key, '__multiple')) {
                    $each = [];
                    $each[0] = strtolower($this->modelName);
                    $each[1] = strtolower($this->modelTableName);
                    $each[2] = strtolower($columnName[0]);
                    array_push($this->multiMigration, $each);
                } else if (count($columnName) == 2) {
                    $fieldContent .= "\n\t\t\t" . '$table->bigInteger("' . $columnName[0] . '_id")->unsigned();';
                    $fieldContent .= "\n\t\t\t" . '$table->foreign("' . $columnName[0] . '_id")->references("id")->on("'.$reltable.'")->onUpdate("RESTRICT")->onDelete("CASCADE");';
                }else{
                    if($field == "date" || $field == "datetime" || $field == "time"){
                        $fieldContent .= "\n\t\t\t" . '$table->String("' . $key . '");';
                    }elseif($field=="boolean"){
                        $fieldContent .= "\n\t\t\t" . '$table->boolean("' . $key . '")->default(0);';
                    }
                    else{
                        $fieldContent .= "\n\t\t\t" . '$table->' . $field . '("' . $key . '");';
                    }
                }

            }else{
                if($field == "date" || $field == "datetime" || $field == "time"){
                    $fieldContent .= "\n\t\t\t" . '$table->String("' . $key . '");';
                }elseif($field=="boolean"){
                    $fieldContent .= "\n\t\t\t" . '$table->boolean("' . $key . '")->default(0);';
                }else{
                    $fieldContent .= "\n\t\t\t" . '$table->' . $field . '("' . $key . '");';
                }

            }
        }
        return '<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create' . $this->modelCamelCase . 'sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("' . $this->modelTableName . 's", function (Blueprint $table) {
            $table->bigIncrements("id");' . $fieldContent . '
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("' . $this->modelTableName . 's");
    }

}';
    }

    private function modelContent($model, $fields = ['name' => 'string'])
    {
        $fillableFields = '';
        $methods = '';
        $imgFiles = '';
        foreach ($fields as $key => $field) {
            if ($key == 'images') {
                $imgFiles = "\n\t".'public $img_files;'."\n";
            }

            $columnName = explode('__', $key);
            $reltable = $columnName[0] . 's';
            if (Schema::hasTable($reltable)) {
                if (count($columnName) == 2) {
                    $methods .= "\n\t\t\t" . 'public function ' . $columnName[0] . '(){
                        return $this->belongsTo(' . $this->toCamelCase($columnName[0]) . '::class,\'' . $columnName[0] . '_id\');
                    }' . "\n";
                    $fillableFields .= "\n\t\t\t" . "'" . $columnName[0] . "_id',";
                } elseif (strpos($key, '__multiple')) {

                    $methods .= "\n\tpublic function " . $columnName[0] ."()
                    {
                        return $" . "this->belongsToMany(" . $this->toCamelCase($columnName[0]) . "::class, '" . $this->modelTableName . "_" . $columnName[0] . "', '" . $this->modelTableName . "_id', '" . $columnName[0] . "_id');
                    }\n\t/**
                    * Alias to eloquent many-to-many relation's attach() method.
                    *
                    * @param mixed $" . $columnName[0] . "
                    *
                    * @return void
                    */
                   public function attach" . $columnName[0] . "($" . $columnName[0] . ")
                   {
                       if (is_object($" . $columnName[0] . ")) {
                        $" . $columnName[0] . " = $" . $columnName[0] . "->getKey();
                       }

                       if (is_array($" . $columnName[0] . ")) {
                        $" . $columnName[0] . " = $" . $columnName[0] . "['id'];
                       }

                       $" . "this" . "->" . $columnName[0] . "()->attach($" . $columnName[0] . ");
                   }

                   /**
                    * Alias to eloquent many-to-many relation's detach() method.
                    *
                    * @param mixed $" . $columnName[0] . "
                    *
                    * @return void
                    */
                   public function detach" . $columnName[0] . "($" . $columnName[0] . ")
                   {
                       if (is_object($" . $columnName[0] . ")) {
                           $" . $columnName[0] . " = $" . $columnName[0] . "->getKey();
                       }

                       if (is_array($" . $columnName[0] . ")) {
                           $" . $columnName[0] . " = $" . $columnName[0] . "['id'];
                       }

                       $" . "this" . "->" . $columnName[0] . "()->deatach($" . $columnName[0] . ");
                   }

                   /**
                    * Attach multiple " . $reltable . " to a user.
                    *
                    * @param mixed $" . $reltable . "
                    *
                    * @return void
                    */
                   public function attach" . $reltable . " ($" . $reltable . ")
                   {
                       foreach ($" . $reltable . " as $" . $columnName[0] . ") {
                           $" . "this" . "->attach" . $columnName[0] . "($" . $columnName[0] . ");
                       }
                   }

                   /**
                    * Detach multiple " . $columnName[0] . " from a user.
                    *
                    * @param mixed $" . $reltable . "
                    *
                    * @return void
                    */
                   public function detach" . $reltable . " ($" . $reltable . " )
                   {
                       foreach ($" . $reltable . " as $" . $columnName[0] . ") {
                            $" . "this" . "->detach" . $columnName[0] . "($" . $columnName[0] . ");
                       }
                   }
               ";
                } else {
                    $fillableFields .= "\n\t\t\t" . "'" . $key . "',";
                }
            } else {
                $fillableFields .= "\n\t\t\t" . "'" . $key . "',";
            }
        }
        return '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ' . $this->modelCamelCase . ' extends Model
{
    protected $table = "' . $this->modelTableName . 's";
    //
    protected $fillable =[' . $fillableFields . '
    ];' . $imgFiles . $methods . '
}';
    }

    private function requestContent($model, $fields = ['name' => 'string'], $type)
    {
        $fieldContent = '';

        if ($type == 'Update' || $type == 'Store') {
            foreach ($fields as $key => $value) {
                $columnName = explode('__', $key);
                $reltable = $columnName[0] . 's';
                if (Schema::hasTable($reltable)) {
                    if (count($columnName) == 2) {
                        $fieldContent .= "\n\t\t\t" . "'" . $columnName[0] . "_id' => [
                            'required'
                        ],";
                    } else if (strpos($key, '__multiple')) {
                        continue;
                    } else if($value!='boolean'){
                        $fieldContent .= "\n\t\t\t" . "'" . $key . "' => [
                            'required'
                        ],";
                    }
                } else {
                    if($value!='boolean'){
                        $fieldContent .= "\n\t\t\t" . "'" . $key . "' => [
                            'required'
                        ],";
                    }
                }
            }
        }

        if(!(Permission::where('name',strtolower($type). '-' . strtolower($this->toPermissionName()))->exists())){
            Permission::create(['name' => strtolower($type) . '-' .strtolower($this->toPermissionName()), 'display_name' => ucfirst($type) . ' ' . $model, 'created_by' => auth()->user()->id]);
        }
        return '<?php

namespace App\Http\Requests\\' . strtolower($this->modelCamelCase) . ';

use App\Models\\' . $this->modelCamelCase . ';
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ' . $this->modelCamelCase . $type . 'Request extends FormRequest
{
    /**
     * Determine if the ' . strtolower($this->modelCamelCase) . ' is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
         if(auth()->check()){
            return auth()->user()->allow(\'' . strtolower($type) . '-' . strtolower($this->toPermissionName()) . '\');
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [' . $fieldContent . '
        ];
    }
}
';
    }

    private function controllerContent($model, $fields)
    {
        $storeMultipleRel = '';
        $inputExcept = '';
        $updateMultipleRel = '';
        $deleteMultipleRel = '';
       $checkbox ='';
        $storeMultipleFiles = property_exists($fields, 'images') ? "\n\t\t"
            .'if(isset($input[\'images\'])&&!is_null($input[\'images\'])) {'."\n\t\t\t"
            .'$input[\'images\'] = json_encode($input[\'images\']);'."\n\t\t"
            .'}else{'."\n\t\t"
            .'$input[\'images\']=\'[]\';'."\n\t\t"
            .'}'."\n\t\t"
            :'';
        $editMultipleFiles =  property_exists($fields, 'images') ? "\n\t\t"
            .'$'.strtolower($this->modelCamelCase).'->img_files=array();'."\n\t\t"
            .'foreach(json_decode($'.strtolower($this->modelCamelCase).'->images) as $gal){'."\n\t\t\t"
            .'if(file_exists(public_path($gal))) {'."\n\t\t\t\t"
            .'$obj = new \stdClass();'."\n\t\t\t\t"
            .'$obj->url = url(\'/\') . \'/\' . $gal;'."\n\t\t\t\t"
            .'list($obj->width, $obj->height, $obj->type, $obj->attr) = getimagesize(public_path($gal));'."\n\t\t\t\t"
            .'$obj->size=filesize(public_path($gal));'."\n\t\t\t\t"
            .'$obj->path = $gal;'."\n\t\t\t\t"
            .'$obj->name = explode(\''.strtolower($this->modelCamelCase).'/\',$gal)[1];'."\n\t\t\t\t"
            .'array_push($'.strtolower($this->modelCamelCase).'->img_files, $obj);'."\n\t\t\t"
            .'}'."\n\t\t"
            .'}'
            :'';
        $updateMultipleFiles =$storeMultipleFiles;
        $deleteMultipleFiles =  property_exists($fields, 'images') ? "\n\t"
            .'$images = json_decode($'.strtolower($this->modelCamelCase).'->images);'."\n\t\t"
            .'if(count($images)){'."\n\t\t\t"
            .'foreach($images as $image){'."\n\t\t\t\t"
            .'Storage::delete(implode(\'public\',explode(\'storage\',$image)));'."\n\t\t\t"
            .'}'."\n\t\t"
            .'}'."\n\t\t"
            :'';
        foreach ($fields as $key => $field) {
            $columnName = explode('__', $key);
            $reltable = $columnName[0] . 's';
            if (Schema::hasTable($reltable) && strpos($key, '__multiple')) {
                $inputExcept .= '"' . $columnName[0] . '_id",';
                $storeMultipleRel .= '
                //Attach ' . strtolower($columnName[0]) . 's to ' . strtolower($this->modelCamelCase) . '
                $' . strtolower($columnName[0]) . 's = [];

                if (is_array($request->get(\'' . strtolower($columnName[0]) . '_id\')) && count($request->get(\'' . strtolower($columnName[0]) . '_id\'))) {
                    foreach ($request->get(\'' . strtolower($columnName[0]) . '_id\') as $' . strtolower($columnName[0]) . ') {
                        if (is_numeric($' . strtolower($columnName[0]) . ')) {
                            array_push($' . strtolower($columnName[0]) . 's, $' . strtolower($columnName[0]) . ');
                        }
                    }
                }
                $model->attach' . strtolower($columnName[0]) . 's($' . strtolower($columnName[0]) . 's);';
                $updateMultipleRel .= ' $' . strtolower($this->modelCamelCase) . '->' . strtolower($columnName[0]) . '()->sync([]);

                //Attach ' . strtolower($columnName[0]) . 's to ' . strtolower($this->modelCamelCase) . '
                $' . strtolower($columnName[0]) . 's = [];

                if (is_array($request->get(\'' . strtolower($columnName[0]) . '_id\')) && count($request->get(\'' . strtolower($columnName[0]) . '_id\'))) {
                    foreach ($request->get(\'' . strtolower($columnName[0]) . '_id\') as $' . strtolower($columnName[0]) . ') {
                        if (is_numeric($' . strtolower($columnName[0]) . ')) {
                            array_push($' . strtolower($columnName[0]) . 's, $' . strtolower($columnName[0]) . ');
                        }
                    }
                }
                $' . strtolower($this->modelCamelCase) . '->attach' . strtolower($columnName[0]) . 's($' . strtolower($columnName[0]) . 's);';
                $deleteMultipleRel .= ' $' . strtolower($this->modelCamelCase) . '->' . strtolower($columnName[0]) . '()->sync([]);';
            }elseif($field=='boolean'){
                $checkbox = "\n\t\t"
                .'if(isset($input[\''.$key.'\'])&&!is_null($input[\''.$key.'\'])) {'."\n\t\t\t"
                .'$input[\''.$key.'\'] = 1;'."\n\t\t"
                .'}'."\n\t\t";
            }
        }


        $storeMedias = property_exists($fields, 'images') ? "\n\t" . 'public function storeMedia(){' . "\n\t\t" . '$input = request()->all();'."\n\t\t"
            .'$images=$input[\'file\']->store(\'public/assets/'.strtolower($this->modelCamelCase).'\');'. "\n\t\t"
            .'$newStd = new \stdClass();' . "\n\t\t"
            .'$newStd->orgName=$input[\'file\']->getClientOriginalName();'. "\n\t\t"
            .'$newStd->path=implode(\'storage\',explode(\'public\',$images));'."\n\t\t"
            .'$newStd->name=implode(\'storage\',explode(\'public\',$images));'."\n\t\t"
            .'return response()->json($newStd);'."\n\t"
            .'}'."\n\t"
            .'public function deleteMedia(){'."\n\t\t"
            .'$input = request()->all();'."\n\t\t"
            .'return response()->json(Storage::delete(implode(\'public\',explode(\'storage\',$input[\'path\']))));'."\n\t"
            .'}' : '';
        $images = property_exists($fields, 'image') ? 'if(isset($input[\'image\'])&&!is_null($input[\'image\'])) {
            $inputPath=$request->image->store(\'public/assets/image\');
            $input[\'image\']="/".implode("storage",explode("public",$inputPath));

        }' : '';

        $updateImages = property_exists($fields, 'image') ? 'if(isset($input["image"])&&!is_null($input["image"])) {
            if(isset($' . strtolower($this->modelCamelCase) . '->image) && !empty($' . strtolower($this->modelCamelCase) . '->image)){
                //deleting previous file
                $imageToDelete = str_replace("/storage","",$' . strtolower($this->modelCamelCase) . '->image);
                Storage::delete("/public".$imageToDelete);
            }
            $inputPath=$request->image->store("public/assets/image");
            $input["image"]="/".implode("storage",explode("public",$inputPath));
        }elseif ($request->has("image")){
            if(is_null($input["image"])){
                if(isset($' . strtolower($this->modelCamelCase) . '->image) && !empty($' . strtolower($this->modelCamelCase) . '->image)){
                    //deleting previous file
                    $imageToDelete = str_replace("/storage","",$' . strtolower($this->modelCamelCase) . '->image);
                    Storage::delete("/public".$imageToDelete);
                }
                $input["image"] = "";
            }
        }' : '';

        $deleteImages = property_exists($fields, 'image') ? 'if(isset($' . strtolower($this->modelCamelCase) . '->image) && !empty($' . strtolower($this->modelCamelCase) . '->image)){
            $image = str_replace("/storage","",$' . strtolower($this->modelCamelCase) . '->image);
            Storage::delete("/public".$image);
        }' : '';

        $files = property_exists($fields, 'file') ? "\n\t" . 'if(isset($input[\'file\'])&&!is_null($input[\'file\'])) {
            $inputfilePath=$request->file->store(\'public/assets/file\');
            $input[\'file\']="/".implode("storage",explode("public",$inputfilePath));

        }' : '';
        $file = property_exists($fields, 'file') ? 'else {
            $input[\'file\'] = \'\';
        }' : '';
        $imag = property_exists($fields, 'image') ? 'else {
            $input[\'image\'] = \'\';
        }' : '';
        return '<?php

namespace App\Http\Controllers;
use App\Models\\' . $this->modelCamelCase . ';
use App\Http\Requests\\' . strtolower($this->modelCamelCase) . '\\' . $this->modelCamelCase . 'CreateRequest;
use App\Http\Requests\\' . strtolower($this->modelCamelCase) . '\\' . $this->modelCamelCase . 'EditRequest;
use App\Http\Requests\\' . strtolower($this->modelCamelCase) . '\\' . $this->modelCamelCase . 'StoreRequest;
use App\Http\Requests\\' . strtolower($this->modelCamelCase) . '\\' . $this->modelCamelCase . 'UpdateRequest;
use App\Http\Requests\\' . strtolower($this->modelCamelCase) . '\\' . $this->modelCamelCase . 'DeleteRequest;
use App\Http\Requests\\' . strtolower($this->modelCamelCase) . '\\' . $this->modelCamelCase . 'ViewRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ' . $this->modelCamelCase . 'Controller extends Controller
{
    /**
     * Display a listing of the ' . strtolower($model) . '
     *
     * @param  \App\\' . $model . '  $model
     * @return \Illuminate\View\View
     */
    public function index(' . $this->modelCamelCase . 'ViewRequest $request,' . $this->modelCamelCase . ' $model)
    {
        return view(\'' . strtolower($this->modelCamelCase) . '.index\', [\'' . strtolower($this->modelCamelCase) . '\' => $model->all()]);
    }

    /**
     * Show the form for creating a new ' . strtolower($model) . '
     *
     * @return \Illuminate\View\View
     */
    public function create(' . $this->modelCamelCase . 'CreateRequest $request)
    {

        return view(\'' . strtolower($this->modelCamelCase) . '.create\');
    }

    /**
     * Store a newly created ' . strtolower($model) . ' in storage
     *
     * @param  \App\Http\Requests\\' . $model . 'Request  $request
     * @param  \App\\' . ucfirst($model) . '  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(' . $this->modelCamelCase . 'StoreRequest $request, ' . ucfirst($this->modelCamelCase) . ' $model)
    {
        $input =$request->except([' . $inputExcept . '\'_token\',\'_method\']);
        ' . $images . $imag . $files . $file .$checkbox. '
        ' . $storeMultipleFiles . '
        $model=$model->create($input);
        ' . $storeMultipleRel . '
        return redirect()->route(\'' . strtolower($this->modelCamelCase) . '.index\')->withStatus(__(\'' . $this->modelName . ' successfully created.\'));
    }

    /**
     * Show the form for editing the specified ' . strtolower($model) . '
     *
     * @param  \App\\' . ucfirst($model) . '  $' . strtolower($model) . '
     * @return \Illuminate\View\View
     */
    public function edit(' . $this->modelCamelCase . 'EditRequest $request,' . $this->modelCamelCase . ' $' . strtolower($this->modelCamelCase) . ')
    {
        '.$editMultipleFiles.'
        return view(\'' . strtolower($this->modelCamelCase) . '.edit\', compact(\'' . strtolower($this->modelCamelCase) . '\'));
    }

    /**
     * Update the specified ' . strtolower($model) . ' in storage
     *
     * @param  \App\Http\Requests\\' . $model . 'Request  $request
     * @param  \App\\' . ucfirst($model) . '  $' . strtolower($model) . '
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(' . $this->modelCamelCase . 'UpdateRequest $request,' . $this->modelCamelCase . '  $' . strtolower($this->modelCamelCase) . ')
    {
        $input =$request->except([' . $inputExcept . '\'_token\',\'_method\']);
          ' . $updateMultipleFiles . '
        ' . $updateImages . $files .$checkbox. '

        $' . strtolower($this->modelCamelCase) . '->update($input);
        ' . $updateMultipleRel . '
        return redirect()->route(\'' . strtolower($this->modelCamelCase) . '.index\')->withStatus(__(\'' . $this->modelName . ' successfully updated.\'));
    }

    /**
     * Remove the specified ' . strtolower($model) . ' from storage
     *
     * @param  \App\\' . ucfirst($model) . '  $' . strtolower($model) . '
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(' . $this->modelCamelCase . 'DeleteRequest $request,' . $this->modelCamelCase . '  $' . strtolower($this->modelCamelCase) . ')
    {
        ' . $deleteImages . '
        ' . $deleteMultipleFiles . '
        ' . $deleteMultipleRel . '
        $' . strtolower($this->modelCamelCase) . '->delete();

        return redirect()->route(\'' . strtolower($this->modelCamelCase) . '.index\')->withStatus(__(\'' . $this->modelName . ' successfully deleted.\'));
    }' . $storeMedias . '
}';
    }

    private function createView($model, $fields = ['name' => 'string'])
    {
        $fieldContent = '';
        $afterScripts = '';
        $imagesScripts = '';
        $imagesStyles = '';
        $initialAfterScripts= '';
        foreach ($fields as $key => $value) {
            $columnName = explode('__', $key);
            $reltable = $columnName[0] . 's';
            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
            if($key=="images"){
                $input ='<div class="needsclick dropzone" id="images-dropzone">
                </div>';
                $imagesStyles .="\n".'@section(\'after-style\')
                <link href="{{ asset(\'assets\') }}/css/dropzone.min.css" rel="stylesheet" />
                @endsection';
                $imagesScripts .= '
                <script src="{{ asset(\'assets\') }}/js/dropzone.min.js"></script>
                <script type="text/javascript">
                  var uploadedImageMap = {}
                  Dropzone.options.imagesDropzone = {
                    url: \'{{ route(\''. strtolower($this->modelCamelCase) . '.storeMedia\') }}\',
                    maxFilesize: 10, // MB
                    acceptedFiles: \'.jpeg,.jpg,.png,.gif\',
                    addRemoveLinks: true,
                    headers: {
                      \'X-CSRF-TOKEN\': "{{ csrf_token() }}"
                    },
                    params: {
                      size: 10,
                      width: 4096,
                      height: 4096
                    },
                    success: function (file, response) {
                      $(\'form\').append(\'<input type="hidden" name="images[]" value="\' + response.name + \'">\')
                      uploadedImageMap[response.orgName] = response.name
                    },
                    removedfile: function (file) {

                      var name = \'\'
                      if (typeof file.file_name !== \'undefined\') {
                        name = file.file_name
                        file.previewElement.remove()
                      } else {
                        if(\'path\' in file){
                            name = file.path
                        }else{
                            name = uploadedImageMap[file.name]
                        }
                        $.ajax({
                            url: \'{{route(\''. strtolower($this->modelCamelCase) .'.deleteMedia\')}}\',
                            type: \'post\',
                            data:{
                                path:name,
                                name:file.name
                            },
                            headers: {
                                \'X-CSRF-TOKEN\': "{{ csrf_token() }}"
                            },
                            dataType: \'json\',
                            success: function (data) {
                                if(data){
                                    $(\'form\').find(\'input[name="images[]"][value="\' + name + \'"]\').remove()
                                    file.previewElement.remove()
                                }
                            }
                        });
                      }
                    },
                    init: function () {
                              @if(isset($'. strtolower($this->modelCamelCase) . ') && $'. strtolower($this->modelCamelCase) . '->img_files)
                      var files =
                      {!! json_encode($'. strtolower($this->modelCamelCase) . '->img_files) !!}
                              for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.url)
                        file.previewElement.classList.add(\'dz-complete\')
                        $(\'form\').append(\'<input type="hidden" name="images[]" value="\' + file.path + \'">\')
                      }
                      @endif
                    },
                    error: function (file, response) {
                      if ($.type(response) === \'string\') {
                        var message = response //dropzone sends it\'s own error messages in string
                      } else {
                        var message = response.errors.file
                      }
                      file.previewElement.classList.add(\'dz-error\')
                      _ref = file.previewElement.querySelectorAll(\'[data-dz-errormessage]\')
                      _results = []
                      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        node = _ref[_i]
                        _results.push(node.textContent = message)
                      }

                      return _results
                    }
                  }
                </script>';
            }elseif($value=='boolean'){
                $input ='<input type="checkbox" name="'.$key.'"/>';
            }
            else if ($key == 'file') {
                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="file" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>
                <button onclick="document.getElementById(\'input-file\').click()" type="button" class="btn btn-fab btn-round btn-primary">
                        <i class="material-icons">attach_file</i>
                      </button>';
            } else if ($key == 'image') {
                $input = ' <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                      <div class="fileinput-new thumbnail img-raised">
                          <img src="http://style.anu.edu.au/_anu/4/images/placeholders/person_8x10.png" rel="nofollow" alt="...">
                      </div>
                      <div class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
                      <div>
        <span onclick="document.getElementById(\'input-image\').click()" class="btn btn-raised btn-round btn-default btn-file">
            <span class="fileinput-new">Select image</span>
            <span class="fileinput-exists">Change</span>
            <input id="input-image" type="file" name="image" />
        </span>
                          <a href="javascript:;" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                          </div>
                          </div>
                 ';
            } else if (Schema::hasTable($reltable)) {

                if (strpos($key, '__multiple')) {
                    $input = '@php
                    $' . $reltable . 's = \App\Models\\' . $this->toCamelCase($columnName[0]) . '::all();
                  @endphp

                              <select class="{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . $columnName[0] . '_id[]" multiple="true">
                                  <option selected disabled value="">' . $this->toCamelCase($columnName[0]) . '</option>
                                  @foreach($' . $reltable . 's as $' . $reltable . ')
                                      <option value="{{$' . $reltable . '->id}}">{{$' . $reltable . '->' . $columnName[1] . '}}</option>
                                  @endforeach
                              </select>';
                } else if (count($columnName) == 2) {
                    $input = '@php
                    $' . $reltable . 's = \App\Models\\' . $this->toCamelCase($columnName[0]) . '::all();
                  @endphp

                              <select class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . $columnName[0] . '_id">
                                  <option selected disabled value="">' . $this->toCamelCase($columnName[0]) . '</option>
                                  @foreach($' . $reltable . 's as $' . $reltable . ')
                                      <option value="{{$' . $reltable . '->id}}">{{$' . $reltable . '->' . $columnName[1] . '}}</option>
                                  @endforeach
                              </select>';
                } else {
                    $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
                }
            } else if ($value == 'text' || $value == 'longText') {
                $input = '<textarea rows="5" class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"></textarea>';
            }else if ($value == 'integer' || $value == 'bigInteger') {
                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
            }else if ($value=='date'){

                $afterScripts .="$('.datepicker').datetimepicker({\n\t\t\t".
                    "format: 'MM/DD/YYYY',
                    icons: {
                        time: \"fa fa-clock-o\",
                        date: \"fa fa-calendar\",
                        up: \"fa fa-chevron-up\",
                        down: \"fa fa-chevron-down\",
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-screenshot',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    }
                });\n\t\t";
                $initialAfterScripts .= "document.getElementById('input-". strtolower($key) ."').value = month + \"/\" + day + \"/\" + year;\n\t\t\t";

                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }} datepicker" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="" required="true" aria-required="true"/>';
            } else if($value == 'datetime'){

                $afterScripts .="$('.datetimepicker').datetimepicker({\n\t\t\t".
                    "icons: {
                            time: \"fa fa-clock-o\",
                            date: \"fa fa-calendar\",
                            up: \"fa fa-chevron-up\",
                            down: \"fa fa-chevron-down\",
                            previous: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            today: 'fa fa-screenshot',
                            clear: 'fa fa-trash',
                            close: 'fa fa-remove'
                        }
                    });\n\t\t";
                $initialAfterScripts .= "document.getElementById('input-". strtolower($key) ."').value = month + \"/\" + day + \"/\" + year +\" \"+ strTime;\n\t\t\t";

                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }} datetimepicker" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="" required="true" aria-required="true"/>';

            }elseif($value=='boolean'){
                $input ='<input type="checkbox" name="'.$key.'"/>';
            }else if($value == 'time'){

                $afterScripts .="$('.timepicker').datetimepicker({\n\t\t\t".
                    "format: 'h:mm A',
                        icons: {
                            time: \"fa fa-clock-o\",
                            date: \"fa fa-calendar\",
                            up: \"fa fa-chevron-up\",
                            down: \"fa fa-chevron-down\",
                            previous: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            today: 'fa fa-screenshot',
                            clear: 'fa fa-trash',
                            close: 'fa fa-remove'
                        }
                   });\n\t\t";

                $initialAfterScripts .= "document.getElementById('input-". strtolower($key) ."').value = strTime;\n\t\t\t";

                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }} timepicker" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="" required="true" aria-required="true"/>';

            }else {
                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
            }
            $fieldContent .= "\n\t\t\t\t\t" . '<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __(\'' . ucfirst(implode(" ", explode("_", $key))) . '\') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has(\'' . strtolower($key) . '\') ? \' has-danger\' : \'\' }}">
                      ' . $input . '
                      @if ($errors->has(\'' . strtolower($key) . '\'))
                        <span id="' . strtolower($key) . '-error" class="error text-danger" for="input-' . strtolower($key) . '">{{ $errors->first(\'' . strtolower($key) . '\') }}</span>
                      @endif
                    </div>
                  </div>
                </div>';
        }
        return '@extends(\'layouts.app\', [\'activePage\' => \'' . strtolower($this->modelCamelCase) . '-management\', \'titlePage\' => __(\'' . ($this->modelName) . ' Management\')])

@section(\'content\')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route(\'' . strtolower($this->modelCamelCase) . '.store\') }}" autocomplete="off" class="form-horizontal" ' . ((property_exists($fields, 'image') || property_exists($fields, 'file')) ? 'enctype="multipart/form-data"' : '') . '>
            @csrf
            @method(\'post\')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __(\'Add ' . $model . '\') }}</h4>
                <p class="card-category"></p>
              </div>

              <div class="card-body ">
               @if(auth()->user()->allow(\'view-\'.strtolower(\'' . $this->toPermissionName() . '\')))
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route(\'' . strtolower($this->modelCamelCase) . '.index\') }}" class="btn btn-sm btn-primary">{{ __(\'Back to list\') }}</a>
                  </div>
                </div>
                @endif' . $fieldContent . '
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __(\'Add ' . $model . '\') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection'.
    $imagesStyles.
    "\n". '@section(\'after-script\')'."\n\t".
    $imagesScripts.
    "<script>\n\t\t".

        "$afterScripts"."\n\t".
        "$(document).ready(
            function () {"."\n\t\t\t".
                "var date = new Date();
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var day = date.getDate();
                var hours = date.getHours();
                  var minutes = date.getMinutes();
                  var ampm = hours >= 12 ? 'pm' : 'am';
                  hours = hours % 12;
                  hours = hours ? hours : 12; // the hour '0' should be '12'
                  minutes = minutes < 10 ? '0'+minutes : minutes;
                  var strTime = hours + ':' + minutes + ' ' + ampm.toUpperCase();"."\n\t\t\t".
                "$initialAfterScripts"."\n\t\t".
            "}
        );
     </script>\n@endsection";
    }

    private function editView($model, $fields = ['name' => 'string'])
    {
        $fieldContent = '';
        $afterScripts='';
        $initialAfterScripts='';
        $imagesScripts='';
        $imagesStyles='';
        foreach ($fields as $key => $value) {
            $columnName = explode('__', $key);
            $reltable = $columnName[0] . 's';
            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\', $' . strtolower($this->modelCamelCase) . '->' . $key . ') }}" required="true" aria-required="true"/>';
            if($key=="images"){
                $input ='<div class="needsclick dropzone" id="images-dropzone">
                </div>';
                $imagesStyles .="\n".'
                @section(\'after-style\')
                <link href="{{ asset(\'assets\') }}/css/dropzone.min.css" rel="stylesheet" />
                @endsection';
                $imagesScripts.='
                <script src="{{ asset(\'assets\') }}/js/dropzone.min.js"></script>
                <script type="text/javascript">
                  var uploadedImageMap = {}
                  Dropzone.options.imagesDropzone = {
                    url: \'{{ route(\''. strtolower($this->modelCamelCase) . '.storeMedia\') }}\',
                    maxFilesize: 10, // MB
                    acceptedFiles: \'.jpeg,.jpg,.png,.gif\',
                    addRemoveLinks: true,
                    headers: {
                      \'X-CSRF-TOKEN\': "{{ csrf_token() }}"
                    },
                    params: {
                      size: 10,
                      width: 4096,
                      height: 4096
                    },
                    success: function (file, response) {
                      $(\'form\').append(\'<input type="hidden" name="images[]" value="\' + response.name + \'">\')
                      uploadedImageMap[response.orgName] = response.name
                    },
                    removedfile: function (file) {

                      var name = \'\'
                      if (typeof file.file_name !== \'undefined\') {
                        name = file.file_name
                        file.previewElement.remove()
                      } else {
                        if(\'path\' in file){
                            name = file.path
                        }else{
                            name = uploadedImageMap[file.name]
                        }
                        $.ajax({
                            url: \'{{route(\''. strtolower($this->modelCamelCase) .'.deleteMedia\')}}\',
                            type: \'post\',
                            data:{
                                path:name,
                                name:file.name
                            },
                            headers: {
                                \'X-CSRF-TOKEN\': "{{ csrf_token() }}"
                            },
                            dataType: \'json\',
                            success: function (data) {
                                if(data){
                                    file.previewElement.remove()
                                    $(\'form\').find(\'input[name="images[]"][value="\' + name + \'"]\').remove()
                                }
                            }
                        });
                      }
                    },
                    init: function () {
                              @if(isset($'. strtolower($this->modelCamelCase) . ') && $'. strtolower($this->modelCamelCase) . '->img_files)
                      var files =
                      {!! json_encode($'. strtolower($this->modelCamelCase) . '->img_files) !!}
                              for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.url)
                        file.previewElement.classList.add(\'dz-complete\')
                        $(\'form\').append(\'<input type="hidden" name="images[]" value="\' + file.path + \'">\')
                      }
                      @endif
                    },
                    error: function (file, response) {
                      if ($.type(response) === \'string\') {
                        var message = response //dropzone sends it\'s own error messages in string
                      } else {
                        var message = response.errors.file
                      }
                      file.previewElement.classList.add(\'dz-error\')
                      _ref = file.previewElement.querySelectorAll(\'[data-dz-errormessage]\')
                      _results = []
                      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        node = _ref[_i]
                        _results.push(node.textContent = message)
                      }

                      return _results
                    }
                  }
                </script>
              ';
            }elseif($value=='boolean'){
                $input ='<input type="checkbox" name="'.$key.'"{{($'.strtolower($this->modelCamelCase).'->'.strtolower($key).'?"checked":"")}}/>';
            }
            else if ($value == 'text' || $value == 'longText') {
                $input = '<textarea rows="5" class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true">{{$' . strtolower($this->modelCamelCase) . '->' . $key . '}}</textarea>';
            } else if ($key == 'file') {
                $input = $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="file" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>
                <button onclick="document.getElementById(\'input-file\').click()" type="button" class="btn btn-fab btn-round btn-primary">
                        <i class="material-icons">attach_file</i>
                      </button>';
            } else if ($key == 'image') {
                $input = ' <div class="fileinput fileinput-exists text-center" data-provides="fileinput">
                      <div class="fileinput-new thumbnail img-raised">
                          <img src="http://style.anu.edu.au/_anu/4/images/placeholders/person_8x10.png" rel="nofollow" alt="...">
                      </div>
                      <div class="fileinput-preview fileinput-exists thumbnail img-raised">
                         <img src="{{url($' . strtolower($this->modelCamelCase) . '->image)}}" rel="nofollow" alt="...">
                      </div>
                      <div>
        <span onclick="document.getElementById(\'input-image\').click()" class="btn btn-raised btn-round btn-default btn-file">
            <span class="fileinput-new">Select image</span>
            <span class="fileinput-exists">Change</span>
            <input id="input-image" type="file" name="image" />
            <input type="hidden"  value="{{url($' . strtolower($this->modelCamelCase) . '->image)}}" name="fileinput" />
        </span>
                          <a href="javascript:;" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                          </div>
                          </div>
                 ';
            } elseif (Schema::hasTable($reltable)) {
                if (strpos($key, '__multiple')) {
                    $input = '@php
                    $' . $reltable . 's = \App\Models\\' . $this->toCamelCase($columnName[0]) . '::all();
                    $selected = $' . strtolower($this->modelCamelCase) . '->' . $columnName[0] . '->pluck("id")->all();
                    @endphp

                    <select class="{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . $columnName[0] . '_id[]" multiple="true">
                        <option disabled value="">' . $this->toCamelCase($columnName[0]) . '</option>
                        @foreach($' . $reltable . 's as $' . $reltable . ')
                        <option value="{{$' . $reltable . '->id}}" {!!in_array($' . $reltable . '->id,$selected)?"selected":""!!}>{{$' . $reltable . '->' . $columnName[1] . '}}</option>
                                                          @endforeach
                                                      </select>' ;
                    } else if (count($columnName)==2) {
                        $input='@php
                                            $' . $reltable . 's = \App\Models\\' . $this->toCamelCase($columnName[0]) . '::all();
                            @endphp

                            <select class="{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . $columnName[0] . '_id" multiple="true">
                                <option selected disabled value="">' . $this->toCamelCase($columnName[0]) . '</option>
                                @foreach($' . $reltable . 's as $' . $reltable . ')
                                <option value="{{$' . $reltable . '->id}}" {!!($' . $reltable . '->id==$' . strtolower($this->modelCamelCase) . '->' . $columnName[0] . '_id' . ')?"selected":""!!}>{{$' . $reltable . '->' . $columnName[1] . '}}</option>
                                @endforeach
                            </select>';




                            }else if($value=='integer'||$value=='bigInteger'){
                            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\',$'.strtolower($this->modelCamelCase).'->'.$key.') }}" required="true" aria-required="true" />';
                            }else if ($value=='date'){

                            $afterScripts .="$('.datepicker').datetimepicker({\n\t\t\t".
                            "format: 'MM/DD/YYYY',
                            icons: {
                            time: \"fa fa-clock-o\",
                            date: \"fa fa-calendar\",
                            up: \"fa fa-chevron-up\",
                            down: \"fa fa-chevron-down\",
                            previous: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            today: 'fa fa-screenshot',
                            clear: 'fa fa-trash',
                            close: 'fa fa-remove'
                            }
                            });\n\t\t";

                            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }} datepicker" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\',$'.strtolower($this->modelCamelCase).'->'.$key.') }}" required="true" aria-required="true" />';
                            } else if($value == 'datetime'){

                            $afterScripts .="$('.datetimepicker').datetimepicker({\n\t\t\t".
                            "icons: {
                            time: \"fa fa-clock-o\",
                            date: \"fa fa-calendar\",
                            up: \"fa fa-chevron-up\",
                            down: \"fa fa-chevron-down\",
                            previous: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            today: 'fa fa-screenshot',
                            clear: 'fa fa-trash',
                            close: 'fa fa-remove'
                            }
                            });\n\t\t";

                            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }} datetimepicker" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\',$'.strtolower($this->modelCamelCase).'->'.$key.') }}" required="true" aria-required="true" />';

                            }else if($value == 'time'){

                            $afterScripts .="$('.timepicker').datetimepicker({\n\t\t\t".
                            "format: 'h:mm A',
                            icons: {
                            time: \"fa fa-clock-o\",
                            date: \"fa fa-calendar\",
                            up: \"fa fa-chevron-up\",
                            down: \"fa fa-chevron-down\",
                            previous: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            today: 'fa fa-screenshot',
                            clear: 'fa fa-trash',
                            close: 'fa fa-remove'
                            }
                            });\n\t\t";

                            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }} timepicker" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\',$'.strtolower($this->modelCamelCase).'->'.$key.') }}" required="true" aria-required="true" />';

                            }else {
                            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\',$'.strtolower($this->modelCamelCase).'->'.$key.') }}" required="true" aria-required="true" />';
                            }

            } elseif ($value == 'integer' || $value == 'bigInteger') {
                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\',$' . strtolower($this->modelCamelCase) . '->' . $key . ') }}" required="true" aria-required="true"/>';
            } else {
                $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="text" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\',$' . strtolower($this->modelCamelCase) . '->' . $key . ') }}" required="true" aria-required="true"/>';
            }

            $fieldContent .= "\n\t\t\t\t\t" . '<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __(\'' . ucfirst(implode(" ", explode("_", $key))) . '\') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has(\'' . strtolower($key) . '\') ? \' has-danger\' : \'\' }}">
                      ' . $input . '
                      @if ($errors->has(\'' . strtolower($key) . '\'))
                        <span id="' . strtolower($key) . '-error" class="error text-danger" for="input-' . strtolower($key) . '">{{ $errors->first(\'' . strtolower($key) . '\') }}</span>
                      @endif
                    </div>
                  </div>
                </div>';
        }
        return '@extends(\'layouts.app\', [\'activePage\' => \'' . strtolower($this->modelCamelCase) . '-management\', \'titlePage\' => __(\'' . $this->modelName . ' Management\')])

@section(\'content\')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route(\'' . strtolower($this->modelCamelCase) . '.update\', $' . strtolower($this->modelCamelCase) . ') }}" autocomplete="off" class="form-horizontal" ' . ((property_exists($fields, 'image') || property_exists($fields, 'file')) ? 'enctype="multipart/form-data"' : '') . '>
            @csrf
            @method(\'put\')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __(\'Edit ' . $model . '\') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                 @if(auth()->user()->allow(\'view-\'.strtolower(\'' . $this->toPermissionName() . '\')))
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route(\'' . strtolower($this->modelCamelCase) . '.index\') }}" class="btn btn-sm btn-primary">{{ __(\'Back to list\') }}</a>
                  </div>
                </div>
                @endif' . $fieldContent . '
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __(\'Save\') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection'.
$imagesStyles.
        "\n". '@section(\'after-script\')'."\n\t".
        $imagesScripts.
        "<script>\n\t\t".

        "$afterScripts"."\n\t".
     "</script>\n@endsection";
    }

    private function viewIndex($model, $fields = ['name' => 'string'])
    {
        $titleContent = '';
        $bodyContent = '';
        $fieldKey = array();
        $fieldMe = array();
        array_push($fieldKey, 'id');
        foreach ($fields as $key => $value) {
            $columnName = explode('__', $key);
            $reltable = $columnName[0] . 's';
            $titleContent .= "\n\t\t\t\t\t\t" . '<th>
                            {{ __("' . ucfirst(implode(" ", explode("_", $columnName[0]))) . '") }}
                          </th>';
            if ($key == 'image') {
                $bodyContent .= "\n\t\t\t\t\t\t" . '<td>
                            <div class="fileinput fileinput-new text-center">
                            <div class="fileinput-new thumbnail img-raised">
                                <img src="{{ url("/").$model->' . $key . ' }}" rel="nofollow" alt="Image not found">
                            </div>
                          </td>';
            } elseif ($key == 'file') {
                $bodyContent .= "\n\t\t\t\t\t\t" . '<td>
                            <div class="fileinput-new thumbnail img-raised">
                                <a href="{{ url("/").$model->' . $key . ' }}">Download</a>
                            </div>
                          </td>';
            }elseif($key=='images'){
                $bodyContent.= "\n\t\t\t\t\t\t" . '<td>@if(isset($model->images)&&is_array(json_decode($model->images)))
                <div class="avatar avatar-sm rounded" style="display:flex;width:100px; height:100px;overflow: hidden;">

                    <img src="{{url(\'/\').\'/\'.json_decode($model->images)[0]}}" alt="" style="max-width: {{0.5*100}}px;">
                    @if(count(json_decode($model->images))>=2)
                    <img src="{{url(\'/\').\'/\'.json_decode($model->images)[1]}}" alt="" style="max-width: {{0.5*100}}px;">
                    @endif
                </div>
                @endif </td>';
            } elseif (Schema::hasTable($reltable)) {
                if (count($columnName) == 2) {
                    $bodyContent .= "\n\t\t\t\t\t\t" . '<td>
                        {{$model->' . $columnName[0] . '->' . $columnName[1] . '}}
                    </td>';
                } elseif (strpos($key, '__multiple')) {
                    $bodyContent .= "\n\t\t\t\t\t\t" . '<td>
                        {{implode(", ",$model->' . $columnName[0] . '->pluck("' . $columnName[1] . '")->all())}}
                    </td>';
                } else {
                    $bodyContent .= "\n\t\t\t\t\t\t" . '<td>
                        {{$model->' . $key . '}}
                    </td>';
                }
            } else {
                $bodyContent .= "\n\t\t\t\t\t\t" . '<td>
                                {{$model->' . $key . '}}
                          </td>';
            }

            array_push($fieldKey, $key);
        }
        array_push($fieldKey, 'created_at');
        $fieldMe = $fieldKey;
        array_push($fieldKey, 'actions');
        return '@extends(\'layouts.app\', [\'activePage\' => \'' . strtolower($this->modelCamelCase) . '-management\', \'titlePage\' => __(\'' . $this->modelName . ' Management\')])

@section(\'content\')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title ">{{ __(\'' . $this->modelName . '\') }}</h4>
                <p class="card-category"> {{ __(\'Here you can manage ' . strtolower($model) . '\') }}</p>
              </div>
              <div class="card-body">
                @if (session(\'status\'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="material-icons">close</i>
                        </button>
                        <span>{{ session(\'status\') }}</span>
                      </div>
                    </div>
                  </div>
                @endif
                 @if(auth()->user()->allow(\'create-\'.strtolower(\'' . $this->toPermissionName() . '\')))
                <div class="row">
                    <div class="col-5">
                        <a href="{{ route(\'' . strtolower($this->modelCamelCase) . '.create\') }}" class="btn btn-sm btn-primary">{{ __(\'Add ' . $model . '\') }}</a>
                    </div>
                </div>
                @endif
                <div class="table-responsive">
                  <table id="dataTable" class="table">
                    <thead class=" text-primary">
                    <th>
                        {{ __(\'Id\') }}
                    </th>' . $titleContent . '
                      <th>
                        {{ __(\'Creation date\') }}
                      </th>
                      <th class="text-right">
                        {{ __(\'Actions\') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($' . strtolower($this->modelCamelCase) . ' as $model)
                        <tr>
                            <td>
                                {{$model->id}}
                            </td>' . $bodyContent . '
                          <td>
                            {{ $model->created_at->format(\'Y/m/d\') }}
                          </td>
                          <td class="td-actions text-right">
                                 @if(auth()->user()->allow(\'delete-\'.strtolower(\'' . $this->toPermissionName() . '\')))
                              <form action="{{ route(\'' . strtolower($this->modelCamelCase) . '.destroy\', $model) }}" method="post">
                                  @csrf
                                  @method(\'delete\')
                                  @endif
                                  @if(auth()->user()->allow(\'edit-\'.strtolower(\'' . $this->toPermissionName() . '\')))
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route(\'' . strtolower($this->modelCamelCase) . '.edit\', $model) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  @endif
                                   @if(auth()->user()->allow(\'delete-\'.strtolower(\'' . $this->toPermissionName() . '\')))
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm(\'{{ __("Are you sure you want to delete this ' . strtolower($model) . '?") }}\') ? this.parentElement.submit() : \'\'">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                                  @endif
                              </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section(\'after-script\')
    <script type="text/javascript">
        $(document).ready(function () {
            $(\'#dataTable\').DataTable( {
                autoWidth: false,
                columnDefs: [
                    {
                        targets: [\'_all\'],
                        className: \'mdc-data-table__cell\'
                    }
                ],
            } );
        })

    </script>
@endsection';
    }

    private function toCamelCase($name)
    {
        return implode('', explode('_', implode('', explode(' ', ucwords($name)))));
    }

    private function toTableName($name)
    {
        return implode('_', explode(' ', strtolower($name)));
    }
    private function toPermissionName()
    {
        return implode('-', explode(' ', strtolower($this->modelName)));
    }
}
