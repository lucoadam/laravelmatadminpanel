@extends('layouts.app', ['activePage' => 'permission-management', 'titlePage' => __('Permission Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('permission.store') }}" autocomplete="off" class="form-horizontal" >
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">add</i>
                   </div>
                <h4 class="card-title">{{ __('Add Permission') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('permission.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
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
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Display name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('display_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('display_name') ? ' is-invalid' : '' }}" name="display_name" id="input-display_name" type="text" placeholder="{{ __('Display_name') }}" value="{{ old('display_name') }}" required="true" aria-required="true"/>
                      @if ($errors->has('display_name'))
                        <span id="display_name-error" class="error text-danger" for="input-display_name">{{ $errors->first('display_name') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Add Permission') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
