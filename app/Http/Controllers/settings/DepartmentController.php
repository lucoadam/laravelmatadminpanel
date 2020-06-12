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

class DepartmentController extends Controller
{
    private $modelCamelCase;
    private $modelTableName;
    private $modelName;
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
        return view('settings.departments.index',['departments' => $model->orderBy('id','desc')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('settings.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request, Module $department)
    {
//       dd($request->all());
        if ($request->all()['name'] != 'module') {
            $this->modelCamelCase=$this->toCamelCase($request->all()['name']);
            $this->modelTableName=$this->toTableName($request->all()['name']);

            $modelName = ucwords(strtolower($request->all()['name']));
            $this->modelName = ucwords(strtolower($request->all()['name']));
            if($modelName=="Role"||$modelName=='Permission'||$modelName=='User'||$modelName=='Module'){
                return redirect()->route('settings.department.create');
            }
            $fields = json_decode($request->all()['field']);//array('name'=>'string','country'=>'string','city'=>'text','salary'=>'integer');
            $basePath = explode('public', public_path())[0];
            if (!Schema::hasTable(strtolower($this->modelTableName) . 's')) {
                $maxMigration=explode('_',DB::table('migrations')->max('migration'))[3]+1;
                $migrationPath = $basePath . 'database/migrations/' . now()->format('Y_m_d') . '_' . $maxMigration . '_create_' . strtolower($this->modelTableName) . 's_table.php';
                $migrationContent = $this->migrationContent(strtolower($modelName), $fields);
                $migrationGenerate = File::put($migrationPath, $migrationContent);
                exec('cd ' . $basePath . ' && php artisan migrate');
            }
            $modelPath = $basePath . 'app/Models/' . $this->modelCamelCase . '.php';
            $modelContent = $this->modelContent($modelName, $fields);
            $modelGenerate = File::put($modelPath, $modelContent);

            $requestPath = $basePath . 'app/Http/Requests/' . strtolower($this->modelCamelCase);
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

            $controllerPath = $basePath . '/app/Http/Controllers/' . $this->modelCamelCase . 'Controller.php';
            $controllerContent = $this->controllerContent($modelName,$fields);
            $contollergenerate = File::put($controllerPath, $controllerContent);
            $viewPath = $basePath . 'resources/views/' . strtolower($this->modelCamelCase);
            File::isDirectory($viewPath) or File::makeDirectory($viewPath, 0777, true, true);
            $createContent = $this->createView($modelName, $fields);
            $createGenerate = File::put($viewPath . '/create.blade.php', $createContent);
            $editContent = $this->editView($modelName, $fields);
            $editGenerate = File::put($viewPath . '/edit.blade.php', $editContent);
            $indexContent = $this->viewIndex($modelName, $fields);
            $indexGenerate = File::put($viewPath . '/index.blade.php', $indexContent);
            $routesPath = $basePath . 'routes/Generator/admin';
            File::isDirectory($routesPath) or File::makeDirectory($routesPath, 0777, true, true);
            File::put($routesPath . '/' . $this->modelCamelCase . '.php', '<?php' . "\n\t" . 'Route::resource(\'' . strtolower($this->modelCamelCase) . '\',\'' . $this->modelCamelCase . 'Controller\');');
            $department->create(['name' => $request->all()['name']]);

            if($request->has('parent')&&!is_null($request->get('parent'))){
                $parent=Menu::firstOrCreate(['name'=>$request->get('parent'),'url'=>'#']);
                Menu::firstOrCreate(['name'=>$request->all()['name'],'url'=>strtolower($this->modelCamelCase).'.index','parent_id'=>$parent->id]);
            }else{
                Menu::firstOrCreate(['name'=>$request->all()['name'],'url'=>strtolower($this->modelCamelCase).'.index']);
            }
        }
        return redirect()->route('settings.department.index')->withStatus(__('Department successfully created.'));

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
    public function edit(Module $department)
    {
        return view('settings.departments.edit',compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $department)
    {
        //
        $department->update($request->all());
        return redirect()->route('settings.department.index')->withStatus(__('Department successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $department)
    {
        //
        $modelName = ucwords($department->name);
        $this->modelName = ucwords($department->name);
        $this->modelTableName = $this->toTableName($this->modelName);
        $this->modelCamelCase = $this->toCamelCase($this->modelName);


        $permissions=Permission::where('name','like','%'.strtolower($this->modelCamelCase))->get();
        if($modelName!='Menus'){
        $mig = scandir(base_path() . '/database/migrations');
        // DB::table('migrations')->where('migration','2019_11_08_830097_create_librarys_table')->delete();
        $mArray = preg_grep('/' . strtolower($this->modelTableName) . 's/', $mig);

        $basePath = explode('public', public_path())[0];
        $modelPath = $basePath . 'app/Models/' . $this->modelCamelCase . '.php';
        $requestPath = $basePath . 'app/Http/Requests/' .strtolower($this->modelCamelCase);
        $controllerPath = $basePath . '/app/Http/Controllers/' . $this->modelCamelCase . 'Controller.php';
        $viewPath = $basePath . 'resources/views/' . strtolower($this->modelCamelCase);
        $routesPath = $basePath . 'routes/Generator/admin';
        Schema::dropIfExists(strtolower($this->modelTableName) . 's');

        if (count($mArray) == 1) {
            $migrationName = array_pop($mArray);
            $path = base_path() . '/database/migrations/' . $migrationName;
            $migrationName = explode('.', $migrationName)[0];
            // dd(File::exists($path));
            // return;
            if (File::exists($path)) {
                File::delete($path);
                DB::table('migrations')->where('migration', $migrationName)->delete();
            }

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
        if(File::isDirectory($requestPath)){
            File::deleteDirectory($requestPath);
        }
        if(File::isDirectory($viewPath)){
            File::deleteDirectory($viewPath);
        }
            $menu = Menu::where('url', strtolower($this->modelCamelCase) . '.index')->first();
            if (!is_null($menu)) {
                if($menu->parent_id!=0&&Menu::where('parent_id',$menu->parent_id)->count()==1) {
                    $pMenu = Menu::find($menu->parent_id);
                    if($pMenu->exists()){
                        $menu->delete();
                        $pMenu->delete();

                    }
                }else{
                    $menu->delete();
                }
            }
        foreach ($permissions as $permission){
            $permission->delete();
        }

        $department->delete();
    }
        return redirect()->route('settings.department.index')->withStatus(__('Department successfully deleted.'));
    }


    private function migrationContent($table,$fields=['name'=>'string']){
        $fieldContent = '';
        foreach($fields as $key=>$field){
            if(count(explode('_',$key))==2&&explode('_',$key)[1]=='id'){
                $reltable = explode('_',$key)[0].'s';
                if(Schema::hasTable($reltable)){
                    $fieldContent .= "\n\t\t\t" . '$table->bigInteger("' . $key . '")->unsigned();';
                    $fieldContent .= "\n\t\t\t" . '$table->foreign("' . $key . '")->references("id")->on("'.$reltable.'")->onUpdate("RESTRICT")->onDelete("CASCADE");';
                    //    $table->foreign('department_id')->references('id')->on('departments')->onUpdate('RESTRICT')->onDelete('CASCADE');
                }else{
                    $fieldContent .= "\n\t\t\t" . '$table->' . $field . '("' . $key . '");';
                }
            }else {
                $fieldContent .= "\n\t\t\t" . '$table->' . $field . '("' . $key . '");';
            }
        }
        return '<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create'.$this->modelCamelCase.'sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("'.$this->modelTableName.'s", function (Blueprint $table) {
            $table->bigIncrements("id");'.$fieldContent.'
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
        Schema::drop("'.$this->modelTableName.'s");
    }

}';
    }

    private function modelContent($model,$fields=['name'=>'string']){
        $fillableFields = '';
        $methods ='';
        foreach($fields as $key=>$field){
            $fillableFields .="\n\t\t"."'".$key."',";
            if(count(explode('_',$key))==2&&explode('_',$key)[1]=='id') {
                $reltable = explode('_', $key)[0];
                if (Schema::hasTable($reltable.'s')) {
                    $methods="\n\t\t\t".'public function '.$reltable.'(){
        return $this->belongsTo('.$this->toCamelCase($reltable).'::class,\''.$reltable.'_id\');
    }';
                }
            }
        }
        return '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class '.$this->modelCamelCase.' extends Model
{
    protected $table = "'.$this->modelTableName.'s";
    //
    protected $fillable =['.$fillableFields.'
    ];'.$methods.'
}';
    }

    private function requestContent($model,$fields=['name'=>'string'],$type){
        $fieldContent='';

        if($type=='Update'||$type=='Store') {
            foreach($fields as $key=>$value){
                $fieldContent .= "\n\t\t\t"."'".$key."' => [
                'required'
            ],";
            }
        }

        Permission::create(['name'=>strtolower($type).'-'.strtolower($this->modelCamelCase),'display_name'=>ucfirst($type).' '.$model,'created_by'=>auth()->user()->id]);
        return '<?php

namespace App\Http\Requests\\'.strtolower($this->modelCamelCase).';

use App\Models\\'.$this->modelCamelCase.';
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class '.$this->modelCamelCase.$type.'Request extends FormRequest
{
    /**
     * Determine if the '.strtolower($this->modelCamelCase).' is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
         if(auth()->check()){
            return auth()->user()->allow(\''.strtolower($type).'-'.strtolower($this->toPermissionName()).'\');
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
        return ['.$fieldContent.'
        ];
    }
}
';
    }

    private function controllerContent($model,$fields){
        $images= property_exists($fields,'image')?'if(isset($input[\'image\'])&&!is_null($input[\'image\'])) {
            $inputPath=$request->image->store(\'public/assets/image\');
            $input[\'image\']="/".implode("storage",explode("public",$inputPath));

        }':'';
        $files= property_exists($fields,'file')?"\n\t".'if(isset($input[\'file\'])&&!is_null($input[\'file\'])) {
            $inputfilePath=$request->file->store(\'public/assets/file\');
            $input[\'file\']="/".implode("storage",explode("public",$inputfilePath));

        }':'';
        $file=property_exists($fields,'file')?'else {
            $input[\'file\'] = \'\';
        }':'';
        $imag=property_exists($fields,'image')?'else {
            $input[\'image\'] = \'\';
        }':'';
        return '<?php

namespace App\Http\Controllers;
use App\Models\\'.$this->modelCamelCase.';
use App\Http\Requests\\'.strtolower($this->modelCamelCase).'\\'.$this->modelCamelCase.'CreateRequest;
use App\Http\Requests\\'.strtolower($this->modelCamelCase).'\\'.$this->modelCamelCase.'EditRequest;
use App\Http\Requests\\'.strtolower($this->modelCamelCase).'\\'.$this->modelCamelCase.'StoreRequest;
use App\Http\Requests\\'.strtolower($this->modelCamelCase).'\\'.$this->modelCamelCase.'UpdateRequest;
use App\Http\Requests\\'.strtolower($this->modelCamelCase).'\\'.$this->modelCamelCase.'DeleteRequest;
use App\Http\Requests\\'.strtolower($this->modelCamelCase).'\\'.$this->modelCamelCase.'ViewRequest;
use Illuminate\Support\Facades\Hash;

class '.$this->modelCamelCase.'Controller extends Controller
{
    /**
     * Display a listing of the '.strtolower($model).'
     *
     * @param  \App\\' . $model . '  $model
     * @return \Illuminate\View\View
     */
    public function index('.$this->modelCamelCase.'ViewRequest $request,' . $this->modelCamelCase .' $model)
    {
        return view(\''.strtolower($this->modelCamelCase).'.index\', [\''.strtolower($this->modelCamelCase).'\' => $model->all()]);
    }

    /**
     * Show the form for creating a new '.strtolower($model).'
     *
     * @return \Illuminate\View\View
     */
    public function create('.$this->modelCamelCase.'CreateRequest $request)
    {

        return view(\''.strtolower($this->modelCamelCase).'.create\');
    }

    /**
     * Store a newly created '.strtolower($model).' in storage
     *
     * @param  \App\Http\Requests\\'.$model.'Request  $request
     * @param  \App\\'.ucfirst($model).'  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store('.$this->modelCamelCase.'StoreRequest $request, '.ucfirst($this->modelCamelCase).' $model)
    {
        $input =$request->all();
        '.$images.$imag.$files.$file.'
        $model->create($input);
        return redirect()->route(\''.strtolower($this->modelCamelCase).'.index\')->withStatus(__(\''.$this->modelName.' successfully created.\'));
    }

    /**
     * Show the form for editing the specified '.strtolower($model).'
     *
     * @param  \App\\'.ucfirst($model).'  $'.strtolower($model).'
     * @return \Illuminate\View\View
     */
    public function edit('.$this->modelCamelCase.'EditRequest $request,'.$this->modelCamelCase.' $'.strtolower($this->modelCamelCase).')
    {
        return view(\''.strtolower($this->modelCamelCase).'.edit\', compact(\''.strtolower($this->modelCamelCase).'\'));
    }

    /**
     * Update the specified '.strtolower($model).' in storage
     *
     * @param  \App\Http\Requests\\'.$model.'Request  $request
     * @param  \App\\'.ucfirst($model).'  $'.strtolower($model).'
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update('.$this->modelCamelCase.'UpdateRequest $request,'.$this->modelCamelCase.'  $'.strtolower($this->modelCamelCase).')
    {
          $input =$request->all();
        '.$images.$files.'

        $'.strtolower($this->modelCamelCase).'->update($input);
        return redirect()->route(\''.strtolower($this->modelCamelCase).'.index\')->withStatus(__(\''.$this->modelName.' successfully updated.\'));
    }

    /**
     * Remove the specified '.strtolower($model).' from storage
     *
     * @param  \App\\'.ucfirst($model).'  $'.strtolower($model).'
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy('.$this->modelCamelCase.'DeleteRequest $request,'.$this->modelCamelCase.'  $'.strtolower($this->modelCamelCase).')
    {
        $'.strtolower($this->modelCamelCase).'->delete();

        return redirect()->route(\''.strtolower($this->modelCamelCase).'.index\')->withStatus(__(\''.$this->modelName.' successfully deleted.\'));
    }
}';

    }

    private function createView($model,$fields=['name'=>'string']){
        $fieldContent = '';
        foreach($fields as $key=>$value){
            $input = '<input class="form-control{{ $errors->has(\''.strtolower($key).'\') ? \' is-invalid\' : \'\' }}" name="'.strtolower($key).'" id="input-'.strtolower($key).'" type="text" placeholder="{{ __(\''.ucfirst($key).'\') }}" value="{{ old(\''.strtolower($key).'\') }}" required="true" aria-required="true"/>';
            if($value=='text'||$value=='longText') {
                $input = '<textarea rows="5" class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"></textarea>';
            }else if($key=='file'){
                $input = $input = '<input class="form-control{{ $errors->has(\''.strtolower($key).'\') ? \' is-invalid\' : \'\' }}" name="'.strtolower($key).'" id="input-'.strtolower($key).'" type="file" placeholder="{{ __(\''.ucfirst($key).'\') }}" value="{{ old(\''.strtolower($key).'\') }}" required="true" aria-required="true"/>
                <button onclick="document.getElementById(\'input-file\').click()" type="button" class="btn btn-fab btn-round btn-primary">
                        <i class="material-icons">attach_file</i>
                      </button>';
            }
            else if($key=='image'){
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
            }else if($value=='integer'||$value=='bigInteger') {
                if(count(explode('_',$key))==2&&explode('_',$key)[1]=='id') {
                    $reltable = explode('_', $key)[0];
                    if (Schema::hasTable($reltable . 's')) {
                        $input = '@php
                    $'.$reltable.'s = \App\Models\\'.ucfirst($reltable).'::all();
                  @endphp

                              <select class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="'.$reltable.'_id">
                                  <option selected disabled value="">'.ucfirst($reltable).'</option>
                                  @foreach($'.$reltable.'s as $'.$reltable.')
                                      <option value="{{$'.$reltable.'->id}}">{{$'.$reltable.'->name}}</option>
                                  @endforeach
                              </select>';
                    }else{
                        $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
                    }
                }else {
                    $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
                }
            }
            $fieldContent .= "\n\t\t\t\t\t".'<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __(\''.ucfirst(implode(" ",explode("_",$key))).'\') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has(\''.strtolower($key).'\') ? \' has-danger\' : \'\' }}">
                      '.$input.'
                      @if ($errors->has(\''.strtolower($key).'\'))
                        <span id="'.strtolower($key).'-error" class="error text-danger" for="input-'.strtolower($key).'">{{ $errors->first(\''.strtolower($key).'\') }}</span>
                      @endif
                    </div>
                  </div>
                </div>';
        }
        return '@extends(\'layouts.app\', [\'activePage\' => \''.strtolower($this->modelCamelCase).'-management\', \'titlePage\' => __(\''.($this->modelName).' Management\')])

@section(\'content\')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route(\''.strtolower($this->modelCamelCase).'.store\') }}" autocomplete="off" class="form-horizontal" '.((property_exists($fields,'image')||property_exists($fields,'file'))?'enctype="multipart/form-data"':'').'>
            @csrf
            @method(\'post\')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __(\'Add '.$model.'\') }}</h4>
                <p class="card-category"></p>
              </div>

              <div class="card-body ">
               @if(auth()->user()->allow(\'view-\'.strtolower(\''.$this->toPermissionName().'\')))
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route(\''.strtolower($this->modelCamelCase).'.index\') }}" class="btn btn-sm btn-primary">{{ __(\'Back to list\') }}</a>
                  </div>
                </div>
                @endif'.$fieldContent.'
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __(\'Add '.$model.'\') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection';
    }

    private function editView($model,$fields=['name'=>'string']){
        $fieldContent = '';
        foreach($fields as $key=>$value){
                $input = '<input class="form-control{{ $errors->has(\''.strtolower($key).'\') ? \' is-invalid\' : \'\' }}" name="'.strtolower($key).'" id="input-'.strtolower($key).'" type="text" placeholder="{{ __(\''.ucfirst($key).'\') }}" value="{{ old(\''.strtolower($key).'\', $'.strtolower($this->modelCamelCase).'->'.$key.') }}" required="true" aria-required="true"/>';
                if($value=='text'||$value=='longText') {
                    $input = '<textarea rows="5" class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true">{{$'.array_key_exists.'->'.$key.'}}</textarea>';
                }else if($key=='file'){
                    $input = $input = '<input class="form-control{{ $errors->has(\''.strtolower($key).'\') ? \' is-invalid\' : \'\' }}" name="'.strtolower($key).'" id="input-'.strtolower($key).'" type="file" placeholder="{{ __(\''.ucfirst($key).'\') }}" value="{{ old(\''.strtolower($key).'\') }}" required="true" aria-required="true"/>
                <button onclick="document.getElementById(\'input-file\').click()" type="button" class="btn btn-fab btn-round btn-primary">
                        <i class="material-icons">attach_file</i>
                      </button>';
                }
                else if($key=='image'){
                    $input = ' <div class="fileinput fileinput-exists text-center" data-provides="fileinput">
                      <div class="fileinput-new thumbnail img-raised">
                          <img src="http://style.anu.edu.au/_anu/4/images/placeholders/person_8x10.png" rel="nofollow" alt="...">
                      </div>
                      <div class="fileinput-preview fileinput-exists thumbnail img-raised">
                         <img src="{{url($'.strtolower($this->modelCamelCase).'->image)}}" rel="nofollow" alt="...">
                      </div>
                      <div>
        <span onclick="document.getElementById(\'input-image\').click()" class="btn btn-raised btn-round btn-default btn-file">
            <span class="fileinput-new">Select image</span>
            <span class="fileinput-exists">Change</span>
            <input id="input-image" type="file" name="image" />
            <input type="hidden"  value="{{url($'.strtolower($this->modelCamelCase).'->image)}}" name="fileinput" />
        </span>
                          <a href="javascript:;" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                          </div>
                          </div>
                 ';
                }else if($value=='integer'||$value=='bigInteger') {
                    if(count(explode('_',$key))==2&&explode('_',$key)[1]=='id') {
                        $reltable = explode('_', $key)[0];
                        if (Schema::hasTable($reltable . 's')) {
                            $input = '@php
                    $'.$reltable.'s = \App\Models\\'.ucfirst($reltable).'::all();
                  @endphp

                              <select class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="'.$reltable.'_id" value="{{ $'.strtolower($model).'->'.$key.'}}">
                                  <option disabled value="">'.ucfirst($reltable).'</option>
                                  @foreach($'.$reltable.'s as $'.$reltable.')
                                      <option value="{{$'.$reltable.'->id}}">{{$'.$reltable.'->name}}</option>
                                  @endforeach
                              </select>';
                        }else{
                            $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
                        }
                    }else {
                        $input = '<input class="form-control{{ $errors->has(\'' . strtolower($key) . '\') ? \' is-invalid\' : \'\' }}" name="' . strtolower($key) . '" id="input-' . strtolower($key) . '" type="number" placeholder="{{ __(\'' . ucfirst($key) . '\') }}" value="{{ old(\'' . strtolower($key) . '\') }}" required="true" aria-required="true"/>';
                    }
                }
                $fieldContent .= "\n\t\t\t\t\t".'<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __(\''.ucfirst(implode(" ",explode("_",$key))).'\') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has(\''.strtolower($key).'\') ? \' has-danger\' : \'\' }}">
                      '.$input.'
                      @if ($errors->has(\''.strtolower($key).'\'))
                        <span id="'.strtolower($key).'-error" class="error text-danger" for="input-'.strtolower($key).'">{{ $errors->first(\''.strtolower($key).'\') }}</span>
                      @endif
                    </div>
                  </div>
                </div>';
            }
        return '@extends(\'layouts.app\', [\'activePage\' => \''.strtolower($this->modelCamelCase).'-management\', \'titlePage\' => __(\''.$this->modelName.' Management\')])

@section(\'content\')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route(\''.strtolower($this->modelCamelCase).'.update\', $'.strtolower($this->modelCamelCase).') }}" autocomplete="off" class="form-horizontal" '.((property_exists($fields,'image')||property_exists($fields,'file'))?'enctype="multipart/form-data"':'').'>
            @csrf
            @method(\'put\')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __(\'Edit '.$model.'\') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                 @if(auth()->user()->allow(\'view-\'.strtolower(\''.$this->toPermissionName().'\')))
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route(\''.strtolower($this->modelCamelCase).'.index\') }}" class="btn btn-sm btn-primary">{{ __(\'Back to list\') }}</a>
                  </div>
                </div>
                @endif'.$fieldContent.'
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
@endsection';
    }

    private function viewIndex($model,$fields=['name'=>'string']){
        $titleContent = '';
        $bodyContent = '';
        $fieldKey= array();
        $fieldMe= array();
        array_push($fieldKey,'id');
        foreach($fields as $key=>$value){
            $titleContent .= "\n\t\t\t\t\t\t".'<th>
                            {{ __("'.ucfirst(implode(" ",explode("_",$key))).'") }}
                          </th>';
            if($key=='image'){
                $bodyContent .= "\n\t\t\t\t\t\t".'<td>
                            <div class="fileinput fileinput-new text-center">
                            <div class="fileinput-new thumbnail img-raised">
                                <img src="{{ url("/").$model->'.$key.' }}" rel="nofollow" alt="Image not found">
                            </div>
                          </td>';
            }elseif($key=='file'){
                $bodyContent .= "\n\t\t\t\t\t\t".'<td>
                            <div class="fileinput-new thumbnail img-raised">
                                <a href="{{ url("/").$model->'.$key.' }}">Download</a>
                            </div>
                          </td>';
            }else{
                $bodyContent .= "\n\t\t\t\t\t\t".'<td>
                                {{$model->'.$key.'}}
                          </td>';
            }

            array_push($fieldKey,$key);
        }
        array_push($fieldKey,'created_at');
        $fieldMe = $fieldKey;
        array_push($fieldKey,'actions');
        return '@extends(\'layouts.app\', [\'activePage\' => \''.strtolower($this->modelCamelCase).'-management\', \'titlePage\' => __(\''.$this->modelName.' Management\')])

@section(\'content\')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title ">{{ __(\''.$this->modelName.'\') }}</h4>
                <p class="card-category"> {{ __(\'Here you can manage '.strtolower($model).'\') }}</p>
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
                 @if(auth()->user()->allow(\'create-\'.strtolower(\''.$this->toPermissionName().'\')))
                <div class="row">
                    <div class="col-5">
                        <a href="{{ route(\''.strtolower($this->modelCamelCase).'.create\') }}" class="btn btn-sm btn-primary">{{ __(\'Add '.$model.'\') }}</a>
                    </div>
                </div>
                @endif
                <div class="table-responsive">
                  <table id="dataTable" class="table">
                    <thead class=" text-primary">
                    <th>
                        {{ __(\'Id\') }}
                    </th>'.$titleContent.'
                      <th>
                        {{ __(\'Creation date\') }}
                      </th>
                      <th class="text-right">
                        {{ __(\'Actions\') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($'.strtolower($this->modelCamelCase).' as $model)
                        <tr>
                            <td>
                                {{$model->id}}
                            </td>'.$bodyContent.'
                          <td>
                            {{ $model->created_at->format(\'Y/m/d\') }}
                          </td>
                          <td class="td-actions text-right">
                                 @if(auth()->user()->allow(\'delete-\'.strtolower(\''.$this->toPermissionName().'\')))
                              <form action="{{ route(\''.strtolower($this->modelCamelCase).'.destroy\', $model) }}" method="post">
                                  @csrf
                                  @method(\'delete\')
                                  @endif
                                  @if(auth()->user()->allow(\'edit-\'.strtolower(\''.$this->toPermissionName().'\')))
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route(\''.strtolower($this->modelCamelCase).'.edit\', $model) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  @endif
                                   @if(auth()->user()->allow(\'delete-\'.strtolower(\''.$this->toPermissionName().'\')))
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm(\'{{ __("Are you sure you want to delete this '.strtolower($model).'?") }}\') ? this.parentElement.submit() : \'\'">
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

    private function toCamelCase($name){
        return implode('',explode(' ',ucwords($name)));
    }

    private function toTableName($name){
        return implode('_',explode(' ',strtolower($name)));
    }
    private function toPermissionName(){
        return implode('-',explode(' ',strtolower($this->modelName)));
    }
}
