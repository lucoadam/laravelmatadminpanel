@extends('layouts.app', ['activePage' => 'role-management', 'titlePage' => __('Role Management')])

@section('content')
    <style>
        .hidden{
            display: none;
        }
    </style>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('role.update', $role) }}" autocomplete="off" class="form-horizontal" >
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit Role') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('role.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $role->name) }}" required="true" aria-required="true"/>
                      @if ($errors->has('name'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                  <div class="row">
                      <label class="col-sm-2 col-form-label">{{ __('Permissions') }}</label>
                      <div class="col-sm-7">
                          <div class="form-group{{ $errors->has('all') ? ' has-danger' : '' }}">
                              <select class="form-control{{$errors->has('all')?'is-invalid':''}}" name="all" id="input-all" required="true" aria-required="true">
                                  <option value="0" {!! $role->all?'selected':'' !!}>All</option>
                                  <option value="1" {!! $role->all?'':'selected' !!}>Custom</option>
                              </select>
                              @if ($errors->has('all'))
                                  <span id="all-error" class="error text-danger" for="input-all">{{ $errors->first('all') }}</span>
                              @endif
                          </div>
                      </div>
                  </div>
                  <br/>
                  <br/>
                  <div class="associated-permission row {{$role->all?'hidden':''}}">
                      <label class="col-sm-2 col-form-label">{{ __('Associated Permissions') }}</label>
                      @php

                          $attachedPermissions=$role->permissions->pluck('id')->toArray();
                          $permissions=\App\Models\Permission::all();
                      @endphp
                      <div class="col-sm-7" style="max-height:400px;overflow-y: scroll;">
                          @if (!is_null($permissions)&&$permissions->count())
                              @foreach ($permissions as $perm)
                                  <label class="control control--checkbox">
                                      <input type="checkbox" name="permissions[{{ $perm->id }}]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" {{ is_array($attachedPermissions) && in_array($perm->id, $attachedPermissions) ? 'checked' : '' }} /> <label for="perm_{{ $perm->id }}">{{ $perm->display_name }}</label>
                                      <div class="control__indicator"></div>
                                  </label>
                                  <br/>
                              @endforeach
                          @else
                              <p>There are no available permissions.</p>
                          @endif
                      </div>
                  </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('after-script')

    <script>
        $(document).ready(function () {

            $('#input-all').on('change',function () {

                if($(this).val()=='1'){
                    $('.associated-permission').removeClass('hidden');
                }else{
                    $('.associated-permission').addClass('hidden');
                }
            })

        })
    </script>
@endsection
