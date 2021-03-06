@extends('layouts.app', ['activePage' => 'module-management', 'titlePage' => __('Module Management')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

<div class="modal fade" id="loginModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                  <div class="card-header card-header-primary text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                      <i class="material-icons">close</i>
                    </button>

                    <h4 class="card-title">Add Fields To Module</h4>

                  </div>
                </div>
                <div class="modal-body">
                    <form class="form" method="" action="">
                        <div class="card-body">

                            <div class="form-group bmd-form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">face</i></div>
                                  </div>
                                  <input placeholder="Column Name" id="nametxt" class="form-control" autofocus="autofocus" name="sname" type="text"/>

                                </div>
                            </div>

                            <div class="form-group bmd-form-group">
                                <div class="input-group">
                                <label for="ColumnType">Column Type</label>

                                  <select id="ColumnType"  class="form-control selectpicker" data-style="btn btn-link" name="type">
                                                <optgroup label="LARAVEL TYPES">
                                                    <option value="increments">INCREMENTS</option>
                                                    <option value="smallIncrements">SMALL INCREMENTS</option>
                                                    <option value="mediumIncrements">MEDIUM INCREMENTS</option>
                                                    <option value="bigIncrements">BIG INCREMENTS</option>
                                                    <option value="timestamps">TIME STAMPS</option>
                                                    <option value="timestampsTz">TIME STAMPS WITH TIMEZONE</option>
                                                    <!--          <option value="nullableTimestamps">NULLABLE TIME STAMPS</option>-->
                                                    <option value="softDeletes">SOFT DELETES</option>
                                                    <option value="rememberToken">REMEMBER TOKEN</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="char">CHAR</option>
                                                    <option value="string" selected="selected">STRING (VARCHAR)</option>
                                                    <option value="text">TEXT</option>
                                                    <option value="mediumText">MEDIUMTEXT</option>
                                                    <option value="longText">LONGTEXT</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="tinyInteger">TINY INTEGER</option>
                                                    <option value="smallInteger">SMALL INTEGER</option>
                                                    <option value="mediumInteger">MEDIUM INTEGER</option>
                                                    <option value="integer">INTEGER</option>
                                                    <option value="bigInteger">BIG INTEGER</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="float">FLOAT</option>
                                                    <option value="decimal">DECIMAL</option>
                                                    <option value="double">DOUBLE</option>
                                                    <option value="boolean">BOOLEAN</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="enum">ENUM</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="date">DATE</option>
                                                    <option value="datetime">DATETIME</option>
                                                    <option value="datetimeTz">DATETIME WITH TIMEZONE</option>
                                                    <option value="time">TIME</option>
                                                    <option value="timeTz">TIME WITH TIMEZONE</option>
                                                    <option value="timestamp">TIMESTAMP</option>
                                                    <option value="timestampTz">TIMESTAMP WITH TIMEZONE</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="binary">BINARY</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="ipAddress">IP ADDRESS</option>
                                                    <option value="macAddress">MAC ADDRESS</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="json">JSON</option>
                                                    <option value="jsonb">JSONB</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="morphs">MORPHS</option>
                                                    <option value="nullableMorphs">NULLABLE MORPHS</option>
                                                    <option disabled="disabled">-</option>
                                                    <option value="uuid">UUID</option>
                                                </optgroup>
                                            </select>


                                </div>
                            </div>

                            <div class="form-group bmd-form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">lock_outline</i></div>
                                  </div>
                                  <!-- <input type="password" placeholder="Password..." class="form-control"> -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="#" onclick="event.preventDefault();addNewContent()" class="btn btn-primary btn-link btn-wd btn-lg">Add</a>
                </div>
            </div>
        </div>
    </div>
</div>
                    <form method="post" action="{{ route('settings.module.store') }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('post')

                        <div class="card ">
                            <div class="card-header card-header-rose card-header-icon">
                                <div class="card-icon">

                                    <i class="material-icons">add</i>
                                  </div>
                                <h4 class="card-title">{{ __('Add Module') }}</h4>

                                </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <a href="{{ route('settings.module.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                                    <div class="col-sm-7">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                                            @if ($errors->has('name'))
                                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if(env('APP_ENV')=='local')
                                    <div class="row">
                                        <div class="col-sm-6 offset-sm-2">
                                            <button class="btn btn-round" onclick="event.preventDefault();" data-toggle="modal" data-target="#loginModal">
                                                Add Fields<i class="material-icons">assignment</i>

                                            </button>
                                            <!-- <p><button class="btn btn-primary" onclick="event.preventDefault();addNewContent()">Add</button></p>-->
                                            <button class="btn btn-round" onclick="event.preventDefault();removePrevious()">Remove</button>
                                        </div>
                                     </div>
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">{{__('Parent Menu(Optional)')}}</label>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <input class="form-control" name="parent"/>
                                            </div>
                                        </div>
                                    </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">{{ __('Field') }}</label>
                    <div class="col-sm-7">
                        <div class="form-group{{ $errors->has('field') ? ' has-danger' : '' }}">
                            <input class="form-control{{ $errors->has('field') ? ' is-invalid' : '' }}" name="field" id="input-field" type="text" placeholder="{{ __('Field') }}" value="{{ old('field') }}" required="true" aria-required="true"/>
                            @if ($errors->has('field'))
                                <span id="field-error" class="error text-danger" for="input-field">{{ $errors->first('field') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Add Module') }}</button>
            </div>
        </div>
        <input id="plural" type="hidden" name="plural"/>
        <input id="singular" type="hidden" name="singular"/>
        </form>
    </div>
    </div>
    </div>
    </div>
 @endsection
 @section('after-script')
 <script src="{{ asset('material')}}/js/pluraize.js"></script>
    <script>
        console.log(pluralize('test'),pluralize.isPlural('test'))
        // pluralize.isPlural('test') //=> false
        // pluralize.isSingular('test')
        window.prevKey=[];
        function addNewContent(){

            var name=document.getElementById('nametxt').value;
            var inputName = document.getElementById('input-name').value;
            if(inputName&&inputName.length>2){
                if(pluralize.isPlural(inputName)){
                    document.getElementById('plural').value=inputName
                    document.getElementById('singular').value=pluralize.singular(inputName)
                }else{
                    document.getElementById('singular').value=inputName
                    document.getElementById('plural').value=pluralize(inputName)
                }

            }
            if(name!='') {
                var type = document.getElementById('ColumnType').value;
                var prev = document.getElementById('input-field').value;
                //console.log("clicked",name,type);
                document.getElementById('nametxt').value = '';
                document.getElementById('ColumnType').value = 'string';
                var arr = prev != '' ? JSON.parse(prev) : {};
                arr[name] = type;
                window.prevKey.push(name);

                console.log(window.prevKey);
                document.getElementById('input-field').value = JSON.stringify(arr);
                document.getElementById('nametxt').focus()
            }
        }
        function removePrevious(){
            var prev = document.getElementById('input-field').value;
            var arr=prev!=''?JSON.parse(prev):{};
            console.log(arr);
            delete arr[window.prevKey[window.prevKey.length-1]];
            window.prevKey.pop();
            document.getElementById('input-field').value=JSON.stringify(arr);
        }
    </script>
@endsection

