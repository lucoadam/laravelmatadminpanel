@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('Module Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('settings.department.store') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Add Module') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('settings.department.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
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
                  <div class="row">
                      <label class="col-sm-2 col-form-label">{{__('Parent Menu(Optional)')}}</label>
                      <div class="col-sm-7">
                          <div class="form-group">
                              <input class="form-control" name="parent"/>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Add Module') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
