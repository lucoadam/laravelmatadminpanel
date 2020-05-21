@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('user.update', $user) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit User') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required="true" aria-required="true"/>
                      @if ($errors->has('name'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Email') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" required />
                      @if ($errors->has('email'))
                        <span id="email-error" class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label" for="input-password">{{ __(' Password') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" input type="password" name="password" id="input-password" placeholder="{{ __('Password') }}" />
                      @if ($errors->has('password'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('password') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label" for="input-password-confirmation">{{ __('Confirm Password') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group">
                      <input class="form-control" name="password_confirmation" id="input-password-confirmation" type="password" placeholder="{{ __('Confirm Password') }}" />
                    </div>
                  </div>
                </div>
                  <br/>
                  <br/>
                  <div class="associated-role row">
                      <label class="col-sm-2 col-form-label">{{ __('Associated Roles') }}</label>

                      <div class="col-sm-7" style="max-height:400px;overflow-y: scroll;">
                          @if (!is_null($roles)&&$roles->count())
                              @foreach ($roles as $role)
                                  <label class="control control--checkbox">
                                      <input data-all="{{$role->all}}" data-permission="{{json_encode($role->permissions->pluck('id')->all())}}" class="associatedRole" type="radio" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ is_array($userRoles) && in_array($role->id, $userRoles) ? 'checked' : '' }} /> <label for="role_{{ $role->id }}">{{ $role->name }}</label>
                                      <div class="control__indicator"></div>
                                  </label>
                                  <br/>
                              @endforeach
                          @else
                              <p>There are no available roles.</p>
                          @endif
                      </div>
                  </div>
                  <div class="associated-permission row ">
                      <label class="col-sm-2 col-form-label">{{ __('Associated Permissions') }}</label>
                      <div class="col-sm-7" style="max-height:400px;overflow-y: scroll;">
                          @if (!is_null($permissions)&&$permissions->count())
                              @foreach ($permissions as $perm)
                                  <label class="control control--checkbox">
                                      <input type="checkbox" name="permissions[{{ $perm->id }}]" class="associatedPermission" value="{{ $perm->id }}" data-id="{{$perm->id}}" id="perm_{{ $perm->id }}" {{ is_array($userPermissions) && in_array($perm->id, $userPermissions) ? 'checked' : '' }} /> <label for="perm_{{ $perm->id }}">{{ $perm->display_name }}</label>
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
        $(document).ready(function(){
           $('.associatedRole').on('click',function(){
                var permission=$(this).data('permission');
                var all =$(this).data('all');
                $('.associatedPermission').each(function(index,item){
                    if(all) {
                       $(item).prop("checked", true);
                    }else{
                        $(item).prop("checked", permission.indexOf($(item).data('id'))!=-1);
                    }
                })
           });
        });
    </script>
@endsection
